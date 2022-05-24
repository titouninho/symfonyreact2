<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(normalizationContext:["groups"=>["users_read"]])]
#[UniqueEntity("email", message: "Un utilisateur ayant cette adresse email existe deja")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(["customers_read"  , "invoices_read" , "invoices_subresource","users_read"])]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(["customers_read" ,"invoices_read", "invoices_subresource", "users_read"])]
    #[assert\NotBlank(message:"L'email doit être renseigné !")]
    #[assert\Email(message:"L'adresse email doit avoir un format valide !")]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[assert\NotBlank(message:"Le mot de passe est obligatoire")]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["customers_read" , "invoices_read","invoices_subresource","users_read"])]
    #[assert\NotBlank(message:"Le prénom est obligatoire")]
    #[assert\Length(min:3, minMessage:"Le prénom doit faire entre 3 et 255 caractères", max:255, maxMessage:"Le prénom doit faire entre 3 et 255 caractères")]

    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(["customers_read" => "invoices_read","invoices_subresource","users_read"])]
    #[assert\NotBlank(message:"Le prénom est obligatoire")]
    #[assert\Length(min:3, minMessage:"Le nom doit faire entre 3 et 255 caractères", max:255, maxMessage:"Le nom doit faire entre 3 et 255 caractères")]
    private $lastName;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Customer::class)]
    private $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): string {
        return $this->email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return Collection<int, Customer>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }
}
