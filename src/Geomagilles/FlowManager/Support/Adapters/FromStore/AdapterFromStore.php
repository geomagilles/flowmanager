<?php
/**
 * This file is part of the Flow framework.
 *
 * (c) Gilles Barbier <geomagilles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Geomagilles\FlowManager\Support\Adapters\FromStore;

use Geomagilles\FlowManager\Models\Input\InputInterface;
use Geomagilles\FlowManager\Models\Output\OutputInterface;
use Geomagilles\FlowManager\Models\Box\BoxInterface;

use Geomagilles\FlowGraph\Factory\GraphFactoryInterface;
use Geomagilles\FlowGraph\Factory\GraphFactory;

/**
 * Adapter retrieving FlowManager\Graph objects
 */
class AdapterFromStore implements AdapterFromStoreInterface
{
    public function __construct(GraphFactoryInterface $factory = null)
    {
        $this->factory = is_null($factory) ? new GraphFactory() : $factory;
    }

    public function getBox(BoxInterface $box)
    {
        // get Box or Graph
        $new = $this->factory->newObject($box->getType(), $box->getName());
        $new->setId($box->getId());

        // add job if any
        if (! is_null($box->getJob())) {
            $new->setJob($box->getJob());
        }
        // add settings
        $new->setSettings($box->getSettings());
        
        // add output points
        foreach ($box->getOutputs() as $output) {
            $out = $this->getOutputPoint($output);
            $new->addOutputPoint($out);
        }

        // add input points
        foreach ($box->getInputs() as $input) {
            $in = $this->getInputPoint($input);
            $new->addInputPoint($in);
        }

        // get recursively if Graph
        if ($new->isGraph()) {
            // get boxes

            foreach ($box->getBoxes() as $b) {
                $new->addBox($this->getBox($b));
            }

            // get arcs
            foreach ($box->getArcs() as $arc) {
                // found out output & input points
                $out = $arc->getOutputPoint();
                $in = $arc->getInputPoint();

                $begin = $new->getBox($out->getBox()->getName());
                $end = $new->getBox($in->getBox()->getName());

                $outputPoint = $begin->getOutputPoint($out->getName());
                $inputPoint = $end->getInputPoint($in->getName());
       
                // create and add arc
                $a = $this->factory->createArc($outputPoint, $inputPoint);
                $a->setId($arc->getId());
                $new->addArc($a);
            }
        }

        return $new;
    }

    protected function getInputPoint(InputInterface $input)
    {
        $new = $this->factory->createPoint($input->getName());
        $new->setId($input->getId());
        if (! is_null($input->getSettings())) {
            $new->setSettings($input->getSettings());
        }

        return $new;
    }

    protected function getOutputPoint(OutputInterface $output)
    {
        $new = $this->factory->createPoint($output->getName());
        $new->setId($output->getId());
        if (! is_null($output->getSettings())) {
            $new->setSettings($output->getSettings());
        }

        return $new;
    }
}
