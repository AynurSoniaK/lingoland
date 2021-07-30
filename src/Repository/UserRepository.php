<?php

namespace App\Repository;

use App\Entity\User;
use App\Data\SearchData;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * Récupère les users en lien avec une recherche
     * @return User[]
     */
    public function findSearch(SearchData $search): array
    {
        $query = $this
            ->createQueryBuilder('u')
            ->select('c','u')
            ->join ('u.language', 'c')
            ->select('v','u')
            ->join ('u.languageLearned', 'v')
            ->select('f','u')
            ->join('u.genre', 'f');
            

        if (!empty($search->q)){
            $query=$query
                ->andWhere('u.name LIKE :q')
                ->setParameter('q',"%{$search->q}%");
        }

        if (!empty($search->ageMin)){
            $query=$query
                ->andWhere('u.age >= :ageMin')
                ->setParameter('ageMin',"$search->ageMin");
        }
        
        if (!empty($search->ageMax)){
            $query=$query
                ->andWhere('u.age <= :ageMax')
                ->setParameter('ageMax',"$search->ageMax");
        }

        if (!empty($search->languages)){
            $query=$query
                ->andWhere('c.id IN (:languages)')
                ->setParameter('languages',$search->languages);
        }

        if (!empty($search->languageslearned)){
            $query=$query
                ->andWhere('v.id IN (:languageslearned)')
                ->setParameter('languageslearned',$search->languageslearned);
        }

        if (!empty($search->gender)){
            $query=$query
                ->andWhere('f.id IN (:gender)')
                ->setParameter('gender',$search->gender);
        }


        return $query->getQuery()->getResult();
    }
}

