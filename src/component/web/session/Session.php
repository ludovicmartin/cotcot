<?php

namespace cotcot\component\web\session;

/**
 * Session.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
abstract class Session {

    /**
     * Get an item.
     * @param string $key item key
     * @param mixed $default default value
     * @return mixed item value
     */
    public abstract function getItem($key, $default = null);

    /**
     * Set an item.
     * @param string $key item key
     * @param mixed $value value
     * @return void
     */
    public abstract function setItem($key, $value);

    /**
     * Get session id.
     * @see session_id
     * @return string session id
     */
    public abstract function getId();

    /**
     * Open session.
     * @see session_start.
     * @return void
     */
    public abstract function open();

    /**
     * Close session.
     * @see session_destroy
     * @return void
     */
    public abstract function close();

    /**
     * Yield session.
     * @see session_write_close
     * @return void
     */
    public abstract function writeClose();

    /**
     * Get session status.
     * @see session_status
     * @return int status
     */
    public abstract function getStatus();

    /**
     * Force to générate a new session ID.
     * @see session_regenerate_id
     * @return void
     */
    public abstract function renew();
}
