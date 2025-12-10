<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Check if user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->id === $order->user_id;
    }

    /**
     * Check if user can update the order.
     */
    public function update(User $user, Order $order): bool
    {
        return $user->isAdmin();
    }

    /**
     * Check if user can delete the order.
     */
    public function delete(User $user, Order $order): bool
    {
        // Admin can delete any order
        if ($user->isAdmin()) {
            return true;
        }
        
        // Customer can only cancel their own pending orders
        return $user->id === $order->user_id && $order->status === 'pending';
    }

    /**
     * Check if user can confirm delivery.
     */
    public function confirmDelivery(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === 'shipped';
    }

    /**
     * Check if user can reorder.
     */
    public function reorder(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Check if user can access payment page.
     */
    public function payment(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}