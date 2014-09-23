<?php

namespace Brick\Doctrine\Types\Geometry;

use Brick\Geo\Geometry;
use Brick\Geo\Proxy\GeometryProxy;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Doctrine type for Geometry.
 */
class GeometryType extends Type
{
    const GEOMETRY           = 'Geometry';
    const POINT              = 'Point';
    const LINESTRING         = 'LineString';
    const POLYGON            = 'Polygon';
    const MULTIPOINT         = 'MultiPoint';
    const MULTILINESTRING    = 'MultiLineString';
    const MULTIPOLYGON       = 'MultiPolygon';
    const GEOMETRYCOLLECTION = 'GeometryCollection';

    /**
     * Default SRID for Geometries;
     * This library assumes that all Geometries are in WGS84 Lon/Lat.
     *
     * @const integer
     */
    const WGS84 = 4326;

    /**
     * Child classes will override this method to return the proper type.
     *
     * @param string $wkb
     *
     * @return Geometry
     */
    protected function createGeometryProxy($wkb)
    {
        return new GeometryProxy($wkb, true);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::GEOMETRY;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return strtoupper($this->getName());
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        return $this->createGeometryProxy($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof Geometry) {
            return $value->asBinary();
        }

        $type = is_object($value) ? get_class($value) : gettype($value);

        throw new \UnexpectedValueException(sprintf('Expected %s, got %s.', Geometry::class, $type));
    }

    /**
     * {@inheritdoc}
     */
    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValueSQL($sqlExpr, AbstractPlatform $platform)
    {
        return sprintf('ST_GeomFromWkb(%s, %s)', $sqlExpr, self::WGS84);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValueSQL($sqlExpr, $platform)
    {
        return sprintf('ST_AsWkb(%s)', $sqlExpr);
    }
}