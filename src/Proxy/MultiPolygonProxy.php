<?php

namespace Brick\Geo\Proxy;

use Brick\Geo\Exception\GeometryException;
use Brick\Geo\IO\WKBReader;
use Brick\Geo\IO\WKTReader;
use Brick\Geo\MultiPolygon;

/**
 * Proxy class for MultiPolygon.
 */
class MultiPolygonProxy extends MultiPolygon implements ProxyInterface
{
    /**
     * The WKT or WKB data.
     *
     * @var string
     */
    private $data;

    /**
     * `true` if WKB, `false` if WKT.
     *
     * @var boolean
     */
    private $isBinary;

    /**
     * The underlying geometry, or NULL if not yet loaded.
     *
     * @var MultiPolygon|null
     */
    private $geometry;

    /**
     * Class constructor.
     *
     * @param string  $data
     * @param boolean $isBinary
     */
    public function __construct($data, $isBinary)
    {
        $this->data     = $data;
        $this->isBinary = $isBinary;
    }

    /**
     * @return void
     *
     * @throws GeometryException
     */
    private function load()
    {
        $geometry = $this->isBinary
            ? (new WKBReader())->read($this->data)
            : (new WKTReader())->read($this->data);

        if (! $geometry instanceof MultiPolygon) {
            throw GeometryException::unexpectedGeometryType(MultiPolygon::class, $geometry);
        }

        $this->geometry = $geometry;
    }

    /**
     * {@inheritdoc}
     */
    public function isLoaded()
    {
        return $this->geometry !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function getGeometry()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromText($wkt)
    {
        return new self($wkt, false);
    }

    /**
     * {@inheritdoc}
     */
    public static function fromBinary($wkb)
    {
        return new self($wkb, true);
    }

    /**
     * {@inheritdoc}
     */
    public function asText()
    {
        if (! $this->isBinary) {
            return $this->data;
        }

        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->asText();
    }

    /**
     * {@inheritdoc}
     */
    public function asBinary()
    {
        if ($this->isBinary) {
            return $this->data;
        }

        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->asBinary();
    }


    /**
     * {@inheritdoc}
     */
    public function getPolygons()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->getPolygons();
    }

    /**
     * {@inheritdoc}
     */
    public function is3D()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->is3D();
    }

    /**
     * {@inheritdoc}
     */
    public function isMeasured()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->isMeasured();
    }

    /**
     * {@inheritdoc}
     */
    public function numGeometries()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->numGeometries();
    }

    /**
     * {@inheritdoc}
     */
    public function geometryN($n)
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->geometryN($n);
    }

    /**
     * {@inheritdoc}
     */
    public function dimension()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->dimension();
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->count();
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->getIterator();
    }

    /**
     * {@inheritdoc}
     */
    public function spatialDimension()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->spatialDimension();
    }

    /**
     * {@inheritdoc}
     */
    public function SRID()
    {
        if ($this->geometry === null) {
            $this->load();
        }

        return $this->geometry->SRID();
    }

}
