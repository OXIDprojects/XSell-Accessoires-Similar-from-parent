<?php
/**
 *    Load Crossselling, Accessoires and SimilarProducts from parent if not set on variant
 *    It also fixes bug https://bugs.oxid-esales.com/view.php?id=2956 in 4.5
 *
 *
 *
 *    This file is part of OXID eShop Community Edition.
 *
 *    OXID eShop Community Edition is free software: you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    OXID eShop Community Edition is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with OXID eShop Community Edition.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author      Mirko Schaal <ms@splinelab.de> http://www.splinelab.de
 * @version     $Id$
 */
class sl_parent_xsell extends sl_parent_xsell_parent // oxArticle
{

    /**
     * Get article long description fixed - see https://bugs.oxid-esales.com/view.php?id=2956
     *
     * @param string $sOxid Article ID
     *
     * @return object $oField field object
     */
    public function getArticleLongDesc( $sOxid = null )
    {
        if ( $this->_oLongDesc === null ) {
            // initializing
            $this->_oLongDesc = new oxField();


            // choosing which to get..
            $sOxid = $sOxid === null ? $this->getId() : $sOxid;
            $sViewName = getViewName( 'oxartextends', $this->getLanguage() );

            $sDbValue = oxDb::getDb()->getOne( "select oxlongdesc from {$sViewName} where oxid = ?", array( $sOxid ) );
            if ( $sDbValue != false ) {
                $this->_oLongDesc->setValue( $sDbValue, oxField::T_RAW );
            } elseif ( $this->oxarticles__oxparentid->value ) {
                $this->_oLongDesc->setValue( $this->getParentArticle()->getArticleLongDesc()->getRawValue(), oxField::T_RAW );
            }
        }
        return $this->_oLongDesc;
    }


    /**
     * Loads and returns array with crosselling information.
     *
     * @return array
     */
    public function getCrossSelling()
    {
        if ( parent::getCrossSelling() ) {
            return parent::getCrossSelling();
        }
        if ( isset( $this->oxarticles__oxparentid->value ) && $this->oxarticles__oxparentid->value ) {
            return $this->getParentArticle()->getCrossSelling();
        }
    }

    /**
     * Loads and returns array with accessoires information.
     *
     * @return array
     */
    public function getAccessoires()
    {
        if ( parent::getAccessoires() ) {
            return parent::getAccessoires();
        }
        if ( isset( $this->oxarticles__oxparentid->value ) && $this->oxarticles__oxparentid->value ) {
            return $this->getParentArticle()->getAccessoires();
        }
    }

    /**
     * Returns a list of similar products.
     *
     * @return array
     */
    public function getSimilarProducts()
    {
        if ( parent::getSimilarProducts() ) {
            return parent::getSimilarProducts();
        }
        if ( isset( $this->oxarticles__oxparentid->value ) && $this->oxarticles__oxparentid->value ) {
            return $this->getParentArticle()->getSimilarProducts();
        }
    }

}
?>