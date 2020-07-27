<?php

namespace App\Entity;

use App\Entity\Profil;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Vich\UploaderBundle\Entity\File;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Vich\Uploadable
 * @ApiResource(
 *  normalizationContext={
 *      "groups"={
 *          "profil:read"
 *      }
 *  },
 *  attributes = {
 *      "security" = "is_granted('ROLE_ADMIN')",
 *      "security_message" = "Accès refusé!"
 *  },
 *  collectionOperations = {
 *      "get" = {
 *          "path" = "/admin/users/",
 *      },
 *      "post" = {
 *          "path" = "/admin/users/",
 *          "deserialize"= false,
 *          "controller"=UserController::class,
 *          
 *      },
 *  },
 *  itemOperations = {
 *      "get" = {
 *          "path" = "/admin/users/{id}/",
 *      },
 *      "put" = {
 *          "path" = "/admin/users/{id}/",
 *      }
 *  }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"profil:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Groups({"profil:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     * @Groups({"profil:read"})
     * @var string|null
     */
    private $avatarType;

    /**
     * @ORM\Column(type="blob")
     * @Groups({"profil:read"})
     */
    private $avatar;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"profil:read"})
     */
    private $profil;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_' . $this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getAvatar()
    {
        return base64_encode(stream_get_contents($this->avatar));
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * Get the value of avatarType
     *
     * @return  string|null
     */
    public function getAvatarType()
    {
        return $this->avatarType;
    }

    /**
     * Set the value of avatarType
     *
     * @param  string|null  $avatarType
     *
     * @return  self
     */
    public function setAvatarType($avatarType)
    {
        $this->avatarType = $avatarType;

        return $this;
    }
}
