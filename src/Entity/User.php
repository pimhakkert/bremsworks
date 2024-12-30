<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $password = null;

    /**
     * @var string The One Time Code generated for this user
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otc = null;

    /**
     * @var \DateTimeImmutable When the OTC was generated for this user
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $otcCreated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $verifyEmailToken = null;

    #[ORM\Column]
    private string $firstName;

    #[ORM\Column]
    private string $lastName;

    #[ORM\Column]
    private bool $wantsMarketingEmails;

    /*
     * The preferred timezone of the user. This only affects the UI. Personalized, generated documents display datetimes
     * in both the user timezone and UTC.
     */
    #[ORM\Column]
    private string $timezone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getOtc(): ?string
    {
        return $this->otc;
    }

    public function setOtc(?string $otc): User
    {
        $this->otc = $otc;
        return $this;
    }

    public function generateOtc()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        $this->otc = $randomString;
        $this->otcCreated = new \DateTimeImmutable();
    }

    public function getOtcCreated(): ?\DateTimeImmutable
    {
        return $this->otcCreated;
    }

    public function getVerifyEmailToken(): ?string
    {
        return $this->verifyEmailToken;
    }

    public function setVerifyEmailToken(?string $verifyEmailToken): User
    {
        $this->verifyEmailToken = $verifyEmailToken;
        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function isWantsMarketingEmails(): bool
    {
        return $this->wantsMarketingEmails;
    }

    public function setWantsMarketingEmails(bool $wantsMarketingEmails): User
    {
        $this->wantsMarketingEmails = $wantsMarketingEmails;
        return $this;
    }

    public function getTimezone(): \DateTimeZone
    {
        return new \DateTimeZone($this->timezone);
    }

    //Used in Twig
    public function getTimezoneName(): string
    {
        return $this->timezone;
    }

    public function setTimezone(\DateTimeZone $timezone): User
    {
        $this->timezone = $timezone->getName();
        return $this;
    }
}
