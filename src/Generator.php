<?php

declare(strict_types=1);

namespace PhpCfdi\SatPysScraper;

readonly class Generator
{
    public function __construct(
        private ScraperInterface $scraper,
        private GeneratorTrackerInterface $tracker = new NullGeneratorTracker(),
    ) {
    }

    public function generate(): Data\Types
    {
        $types = new Data\Types();

        $this->tracker->boot();
        foreach ($this->scraper->obtainTypes() as $typeId => $typeName) {
            $type = $types->addType($typeId, $typeName);
            $this->tracker->type($type);
            foreach ($this->scraper->obtainSegments($typeId) as $segmentId => $segmentName) {
                $segment = $type->addSegment($segmentId, $segmentName);
                $this->tracker->segment($segment);
                foreach ($this->scraper->obtainFamilies($typeId, $segmentId) as $familyId => $familyName) {
                    $family = $segment->addFamily($familyId, $familyName);
                    $this->tracker->family($family);
                    foreach ($this->scraper->obtainClasses($typeId, $segmentId, $familyId) as $classId => $className) {
                        $class = $family->addClass($classId, $className);
                        $this->tracker->class($class);
                    }
                }
            }
        }

        return $types;
    }
}
