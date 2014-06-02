<?php
namespace Fulgurio\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Description of User
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 *
 *         @ORM\Entity(repositoryClass="Fulgurio\UserBundle\Repository\UserRepository")
 *         @ORM\Table(name="fulgurio_user")
 *         @ORM\HasLifecycleCallbacks
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $avatar;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }


    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     * @Assert\File(maxSize="6000000")
     */
    private $avatarFile;

    /**
     * Set avatarFile
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $avatarFile
     */
    public function setAvatarFile(UploadedFile $avatarFile)
    {
        $this->avatarFile = $avatarFile;
        return $this;
    }

    /**
     * Get avatarFile
     *
     * @return Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->avatarFile)
        {
            // faites ce que vous voulez pour générer un nom unique
            $this->avatar = sha1(uniqid(mt_rand(), TRUE)) . '.' . $this->avatarFile->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->avatarFile)
        {
            return;
        }
        $this->avatarFile->move($this->getUploadRootDir(), $this->avatar);
        unset($this->avatarFile);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath())
        {
            unlink($file);
        }
    }

    /**
     * Return url of avatar
     *
     * @return string
     */
    public function getAvatarWebPath()
    {
        return '/' . $this->getUploadDir() . $this->avatar;
    }

    /**
     * Upload directory
     * @return string
     */
    public function getUploadDir()
    {
        return 'uploads/' . $this->getId() . '/';
    }

    public function getAbsolutePath()
    {
        return null === $this->avatar ? null : $this->getUploadRootDir() . '/'. $this->avatar;
    }

    public function getWebPath()
    {
        return null === $this->avatar ? null : $this->getUploadDir() . '/' . $this->avatar;
    }

    /**
     * Get absolut path of upload directory
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        if (is_dir(__DIR__ . '/../../../../web/'))
        {
            $uploadsDir = __DIR__ . '/../../../../web/' . $this->getUploadDir();
        }
        else
        {
            $uploadsDir = __DIR__ . '/../../../../../../web/' . $this->getUploadDir();
        }
        if (!file_exists($uploadsDir))
        {
            mkdir($uploadsDir, 0774, TRUE);
        }
        return $uploadsDir;
    }
}
