<?php

namespace App\Traits;

use App\Audit;
use Illuminate\Support\Str;
use RuntimeException;
use UnexpectedValueException;
use Config;
use App;
use Request;

trait Auditable
{
    /**
     *  Auditable attribute exclusions.
     *
     * @var array
     */
    protected $auditableExclusions = [];

    public static function bootAuditable()
    {
        static::registerModelEvent('created', function ($model){
            $model->audit('created');
        });

        static::registerModelEvent('updated', function ($model){
            $model->audit('updated');
        });

        static::registerModelEvent('deleted', function ($model){
            $model->audit('deleted');
        });

        static::registerModelEvent('restored', function ($model){
            $model->audit('restored');
        });
    }

    public function getAuditableEvents()
    {
        if (isset($this->auditableEvents)) {
            return $this->auditableEvents;
        }

        return [
            'created',
            'updated',
            'deleted',
            'restored',
        ];
    }

    public function audits()
    {
        return $this->morphMany('App\Audit', 'auditable')->orderBy('created_at', 'DESC');
    }

    public function audit($event)
    {
        if (!in_array($event, $this->getAuditableEvents()))
            return;

        $method = 'audit'.Str::studly($event).'Attributes';

        if (!method_exists($this, $method)) {
            throw new RuntimeException(sprintf(
                'Unable to handle "%s" event, %s() method missing',
                $event,
                $method
            ));
        }

        $this->updateAuditExclusions();

        $old = [];
        $new = [];

        $this->{$method}($old, $new);

        $data = $this->transformAudit([
            'old_values'     => json_encode($old, JSON_UNESCAPED_UNICODE),
            'new_values'     => json_encode($new, JSON_UNESCAPED_UNICODE),
            'event'          => $event,
            'auditable_id'   => $this->getKey(),
            'auditable_type' => $this->getMorphClass(),
            'user_id'        => $this->resolveUserId(),
            'url'            => $this->resolveUrl(),
            'ip_address'     => $this->resolveIpAddress(),
            'created_at'     => $this->freshTimestamp(),
        ]);

        Audit::create($data);
    }

    /**
     * {@inheritdoc}
     */
    public function transformAudit(array $data)
    {
        return $data;
    }

    /**
     * Update excluded audit attributes.
     *
     * @return void
     */
    protected function updateAuditExclusions()
    {
        $this->auditableExclusions = $this->getAuditExclude();

        // When in strict mode, hidden and non visible attributes are excluded
        if ($this->getAuditStrict()) {
            $this->auditableExclusions = array_merge($this->auditableExclusions, $this->hidden);

            if (count($this->visible)) {
                $invisible = array_diff(array_keys($this->attributes), $this->visible);
                $this->auditableExclusions = array_merge($this->auditableExclusions, $invisible);
            }
        }

        if (!$this->getAuditTimestamps()) {
            array_push($this->auditableExclusions, static::CREATED_AT, static::UPDATED_AT);

            $this->auditableExclusions[] = defined('static::DELETED_AT') ? static::DELETED_AT : 'deleted_at';
        }

        $attributes = array_except($this->attributes, $this->auditableExclusions);

        foreach ($attributes as $attribute => $value) {
            // Apart from null, non scalar values will be excluded
            if (is_object($value) && !method_exists($value, '__toString') || is_array($value)) {
                $this->auditableExclusions[] = $attribute;
            }
        }
    }

    /**
     * Determine if an attribute is eligible for auditing.
     *
     * @param string $attribute
     *
     * @return bool
     */
    protected function isAttributeAuditable($attribute)
    {
        // The attribute should not be audited
        if (in_array($attribute, $this->auditableExclusions)) {
            return false;
        }

        // The attribute is auditable when explicitly
        // listed or when the include array is empty
        $include = $this->getAuditInclude();

        return in_array($attribute, $include) || empty($include);
    }

    /**
     * Set the old/new attributes corresponding to a created event.
     *
     * @param array $old
     * @param array $new
     *
     * @return void
     */
    protected function auditCreatedAttributes(array &$old, array &$new)
    {
        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeAuditable($attribute)) {
                $new[$attribute] = $value;
            }
        }
    }

    /**
     * Set the old/new attributes corresponding to an updated event.
     *
     * @param array $old
     * @param array $new
     *
     * @return void
     */
    protected function auditUpdatedAttributes(array &$old, array &$new)
    {
        foreach ($this->getDirty() as $attribute => $value) {
            if ($this->isAttributeAuditable($attribute)) {
                $old[$attribute] = array_get($this->original, $attribute);
                $new[$attribute] = array_get($this->attributes, $attribute);
            }
        }
    }

    /**
     * Set the old/new attributes corresponding to a deleted event.
     *
     * @param array $old
     * @param array $new
     *
     * @return void
     */
    protected function auditDeletedAttributes(array &$old, array &$new)
    {
        foreach ($this->attributes as $attribute => $value) {
            if ($this->isAttributeAuditable($attribute)) {
                $old[$attribute] = $value;
            }
        }
    }

    /**
     * Set the old/new attributes corresponding to a restored event.
     *
     * @param array $old
     * @param array $new
     *
     * @return void
     */
    protected function auditRestoredAttributes(array &$old, array &$new)
    {
        // We apply the same logic as the deleted,
        // but the old/new order is swapped
        $this->auditDeletedAttributes($new, $old);
    }

    /**
     * Resolve the ID of the logged User.
     *
     * @throws UnexpectedValueException
     *
     * @return mixed|null
     */
    protected function resolveUserId()
    {
        $resolver = Config::get('audit.user.resolver');

        if (!is_callable($resolver)) {
            throw new UnexpectedValueException('Invalid User resolver type, callable expected');
        }

        return $resolver();
    }

    /**
     * Resolve the current request URL if available.
     *
     * @return string
     */
    protected function resolveUrl()
    {
        if (App::runningInConsole()) {
            return 'console';
        }

        return Request::fullUrl();
    }

    /**
     * Resolve the current IP address.
     *
     * @return string
     */
    protected function resolveIpAddress()
    {
        return Request::ip();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuditInclude()
    {
        return isset($this->auditInclude) ? (array) $this->auditInclude : [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAuditExclude()
    {
        return isset($this->auditExclude) ? (array) $this->auditExclude : [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAuditStrict()
    {
        return isset($this->auditStrict) ? (bool) $this->auditStrict : false;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuditTimestamps()
    {
        return isset($this->auditTimestamps) ? (bool) $this->auditTimestamps : false;
    }
}