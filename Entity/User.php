<?php
namespace Fulgurio\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Description of User
 *
 * @author Vincent GUERARD <v.guerard@fulgurio.net>
 *
 *         @ORM\Entity(repositoryClass="Fulgurio\UserBundle\Repository\UserRepository")
 *         @ORM\Table(name="fulgurio_user")
 *         @Vich\Uploadable
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
     * @ORM\Column(type="string", length=64, nullable=true)
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
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="avatar")
     * @Assert\File(
     *     maxSize="1M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
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
        // Because we need an update of User object when form submit a file,
        // we make a fake update of $avatar
        if ($avatarFile)
        {
            $this->setAvatar(time());
        }
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
}
