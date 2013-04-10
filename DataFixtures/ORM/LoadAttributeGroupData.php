<?php
namespace Pim\Bundle\DemoBundle\DataFixtures\ORM;

use Pim\Bundle\ProductBundle\Entity\AttributeGroup;

use Doctrine\Common\Persistence\ObjectManager;

use Pim\Bundle\ProductBundle\Entity\ProductAttribute;

use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Load fixtures for attribute groups
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 */
class LoadAttributeGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * count groups created to order them
     * @staticvar integer
     */
    static protected $order = 0;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        // create group
        $group = $this->createGroup('General');
        $this->persist($group);

        // link attributes with group
        $attribute = $this->getReference('product-attribute.name');
        $attribute->setGroup($group);
        $this->manager->persist($attribute);

        $attribute = $this->getReference('product-attribute.shortDescription');
        $attribute->setGroup($group);
        $this->manager->persist($attribute);

        $attribute = $this->getReference('product-attribute.longDescription');
        $attribute->setGroup($group);
        $this->manager->persist($attribute);


        $group = $this->createGroup('SEO');
        $this->persist($group);

        $group = $this->createGroup('Marketing');
        $this->persist($group);

        $attribute = $this->getReference('product-attribute.price');
        $attribute->setGroup($group);
        $this->manager->persist($attribute);


        // create group and link attribute
        $group = $this->createGroup('Sizes');
        $this->persist($group);

        $attribute = $this->getReference('product-attribute.size');
        $attribute->setGroup($group);
        $this->manager->persist($attribute);

        // create group and link attribute
        $group = $this->createGroup('Colors');
        $this->persist($group);

        $attribute = $this->getReference('product-attribute.color');
        $attribute->setGroup($group);
        $this->manager->persist($attribute);

        // flush
        $this->manager->flush();


        // translate groups
        $locale = 'fr_FR';

        $this->translate('attribute-group.general', $locale, 'Général');
        $this->translate('attribute-group.seo', $locale, 'SEO');
        $this->translate('attribute-group.marketing', $locale, 'Commercial');
        $this->translate('attribute-group.sizes', $locale, 'Tailles');
        $this->translate('attribute-group.colors', $locale, 'Couleurs');

        $this->manager->flush();
    }

    /**
     * Create a group
     * @param string $name
     *
     * @return \Pim\Bundle\ProductBundle\Entity\AttributeGroup
     */
    protected function createGroup($name)
    {
        $group = new AttributeGroup();
        $group->setName($name);
        $group->setSortOrder(++self::$order);

        return $group;
    }

    /**
     * Persist entity and add reference
     *
     * @param AttributeGroup $group
     */
    protected function persist(AttributeGroup $group)
    {
        $this->manager->persist($group);

        $groupName = strtolower($group->getName());
        $this->addReference('attribute-group.'. $groupName, $group);
    }

    /**
     * Translate a segment
     *
     * @param string $reference Attribute group reference
     * @param string $locale    Locale used
     * @param string $name      Name translated in locale value linked
     */
    protected function translate($reference, $locale, $name)
    {
        $group = $this->getReference($reference);

        $group->setTranslatableLocale($locale);
        $group->setName($name);

        $this->manager->persist($group);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5;
    }
}
