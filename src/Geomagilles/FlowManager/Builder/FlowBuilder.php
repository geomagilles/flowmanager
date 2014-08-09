<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Builder;

use Geomagilles\FlowGraph\Builder\GraphBuilder;
use Geomagilles\FlowGraph\Builder\GraphBuilderInterface;

class FlowBuilder
{
    public function __construct(GraphBuilderInterface $builder = null)
    {
        $this->builder = is_null($builder) ? new GraphBuilder() : $builder;
    }

    public function begin()
    {
        return $this->builder->addBegin();
    }

    public function end()
    {
        return $this->builder->addEnd();
    }
}
