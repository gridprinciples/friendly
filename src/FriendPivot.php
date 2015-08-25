<?php

namespace GridPrinciples\Connectable;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class FriendPivot extends Pivot
{
    use SoftDeletes;

    public function getNameAttribute()
    {
        if (!$this->parentIsPrimaryUser()) {
            return $this->attributes['other_name'];
        }

        return $this->attributes['name'];
    }

    public function getOtherNameAttribute()
    {
        if ($this->parentIsPrimaryUser()) {
            return $this->attributes['other_name'];
        }

        return $this->attributes['name'];
    }

    protected function getStartAttribute($value)
    {
        return $value ? new \Carbon\Carbon($value) : null;
    }

    protected function getEndAttribute($value)
    {
        return $value ? new \Carbon\Carbon($value) : null;
    }

    /**
     * Determines whether or not this pivot's parent is the `user`, not `other_user`
     *
     * @return bool
     */
    protected function parentIsPrimaryUser()
    {
        return (int) $this->parent->getKey() === (int) $this->getAttribute('user_id');
    }
}
