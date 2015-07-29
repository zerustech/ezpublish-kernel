<?php
/**
 * File containing the SignalDispatcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\Core\SignalSlot;

/**
 * A Slot can be assigned to receive a certain Signal.
 *
 * @internal
 * @deprecated CollectorSlot is for internal use, you may use it but it might change from one release to the next.
 */
abstract class CollectorSlot extends Slot
{
    /**
     * Receive the given $signal, react on it, and return data for use by receive after transaction
     *
     * @deprecated This method is for internal use, you may use it but it might change from one release to the next.
     *
     * @param Signal $signal
     * @return mixed Must be fully export & re-creatable, and not contain external object references, resources or similar.
     */
    abstract public function collect(Signal $signal);
}
