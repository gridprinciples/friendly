<?php

namespace GridPrinciples\Connectable\Traits;

use GridPrinciples\Connectable\ConnectionPivot;

trait Connectable
{
    /**
     * Connect the parent model with another model, AKA "send friend request".
     *
     * @param $model
     * @param array $pivot
     * @return mixed
     */
    public function connect($model, $pivot = [])
    {
        return $this->myConnections()->save($model, $pivot);
    }

    /**
     * Remove the connection between these two models.  AKA "block user".
     *
     * @param $model
     * @return bool
     */
    public function disconnect($model)
    {
        $deletedAtLeastOne = false;

        if ($this->connections->count()) {
            foreach ($this->connections as $connection) {
                if ($connection->getKey() == $model->id) {
                    $connection->pivot->delete();
                    $this->resetConnections();

                    $deletedAtLeastOne = true;
                }
            }
        }

        return $deletedAtLeastOne;
    }

    /**
     * Approve an incoming connection request.  AKA "approve request"
     *
     * @param $model
     * @return bool
     */
    public function approve($model)
    {
        $approvedAtLeastOne = false;

        if ($model->connections->count()) {
            foreach ($model->connections as $connection) {
                if ((int) $connection->getKey() === (int) $this->getKey()) {
                    $connection->pivot->approved_at = new \Carbon\Carbon;
                    $connection->pivot->save();
                    $this->resetConnections();

                    $approvedAtLeastOne = true;
                }
            }
        }

        return $approvedAtLeastOne;
    }

    /**
     * Sort of acts like a relationship.  Actually just gets two relations which are collected together.
     *
     * @return mixed
     */
    public function getConnectionsAttribute()
    {
        if (!array_key_exists('connections', $this->relations)) {
            $this->loadConnections();
        }

        return $this->getRelation('connections');
    }

    /**
     * Filters the primary connections by ones that are currently active.
     *
     * @return mixed
     */
    public function getActiveConnectionsAttribute()
    {
        return $this->connections->filter(function ($item) {
            $now = new \Carbon\Carbon;

            if(!$item->pivot->approved_at)
            {
                return false;
            }

            switch (true) {
                // no dates set
                case !$item->pivot->end && !$item->pivot->start:

                    // start is set but is in the future
                case !$item->pivot->end && $item->pivot->start && $item->pivot->start < $now:

                    // end is set but is in the past
                case !$item->pivot->start && $item->pivot->end && $item->pivot->end > $now:

                    // both start and end are set, but we are currently between those dates
                case $item->pivot->start && $item->pivot->start < $now && $item->pivot->end && $item->pivot->end > $now:

                    return true;
                    break;
            }

            // any other scenario fails
            return false;
        });
    }

    /**
     * Eloquent relation defining connections this model initiated.
     *
     * @return mixed
     */
    public function myConnections()
    {
        return $this->belongsToMany(get_called_class(), 'connections', 'user_id', 'other_user_id')
            ->withPivot('name', 'other_name', 'start', 'end', 'approved_at')
            ->whereNull('connections.deleted_at')
            ->withTimestamps();
    }

    /**
     * Eloquent relationship defining incoming connection requests.
     *
     * @return mixed
     */
    public function theirApprovedConnections()
    {
        return $this->belongsToMany(get_called_class(), 'connections', 'other_user_id', 'user_id')
            ->withPivot('name', 'other_name', 'start', 'end', 'approved_at')
            ->whereNull('connections.deleted_at')
            ->whereNotNull('connections.approved_at')
            ->withTimestamps();
    }

    /**
     * Reset the cached connections so they can be rebuilt next time they are requested.
     */
    public function resetConnections()
    {
        unset($this->relations['connections']);
        unset($this->relations['myConnections']);
        unset($this->relations['theirApprovedConnections']);
    }

    /**
     * Load and cache the connections.
     */
    protected function loadConnections()
    {
        if (!array_key_exists('connections', $this->relations)) {
            $connections = $this->mergeConnections();

            $this->setRelation('connections', $connections);
        }
    }

    /**
     * Merge the result of two relationships.
     * @return mixed
     */
    protected function mergeConnections()
    {
        return $this->myConnections->merge($this->theirApprovedConnections);
    }

    public function newPivot($parent, array $attributes, $table, $exists)
    {
        return new ConnectionPivot($parent, $attributes, $table, $exists);
    }
}
