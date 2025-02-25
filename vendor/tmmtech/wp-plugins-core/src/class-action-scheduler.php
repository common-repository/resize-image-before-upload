<?php
/**
 * A class for actions scheduling (using Action Scheduler).
 *
 * A wrapper for Action Scheduler method.
 *
 * @package WP-Plugins-Core
 */

namespace WP_Plugins_Core;

/**
 * Class Action_Scheduler
 */
final class Action_Scheduler {

    /**
     * Schedule an interval, if it wasn't scheduled yet.
     *
     * And delete duplicate intervals.
     *
     * Handles a case when duplicate actions were scheduled, and deletes a file.
     *
     * NOTE: Does nothing if Action Scheduler is not loaded.
     *
     * @param int    $timestamp           When the first instance of the job will run.
     * @param int    $interval_in_seconds How long to wait between runs.
     * @param string $hook                The hook to trigger.
     * @param array  $args Arguments to pass when the hook triggers.
     * @param string $group The group to assign this job to.
     * @param bool   $unique Whether the action should be unique.
     * @param int    $priority Lower values take precedence over higher values. Defaults to 10, with acceptable values falling in the range 0-255.
     */
    public static function schedule_an_interval( $timestamp, $interval_in_seconds, $hook, $args = [], $group = '', $unique = false, $priority = 10 ) {
        $function = function() use ( $timestamp, $interval_in_seconds, $hook, $args, $group, $unique, $priority ) {
            if ( false === as_next_scheduled_action( $hook ) ) {
                as_schedule_recurring_action(
                    $timestamp,
                    $interval_in_seconds,
                    $hook,
                    $args,
                    $group,
                    $unique,
                    $priority
                );
            } elseif ( self::is_duplicate_scheduled( $hook ) ) {
                as_unschedule_all_actions( $hook );

                as_schedule_recurring_action(
                    $timestamp,
                    $interval_in_seconds,
                    $hook,
                    $args,
                    $group,
                    $unique,
                    $priority
                );
            }
        };

        if ( ! class_exists( 'ActionScheduler' ) ) {
            return;
        }

        if ( did_action( 'action_scheduler_init' ) ) {
            $function();
        } else {
            add_action( 'action_scheduler_init', $function );
        }
    }

    /**
     * Check if there are duplicate scheduled actions.
     *
     * @param string $hook Hook name of the scheduled action.
     *
     * @return bool True if duplicate actions found, false otherwise.
     */
    private static function is_duplicate_scheduled( $hook ) {
        $actions = as_get_scheduled_actions(
            [
                'hook'   => $hook,
                'status' => \ActionScheduler_Store::STATUS_PENDING,
            ],
            'ids'
        );

        return count( $actions ) > 1;
    }
}
