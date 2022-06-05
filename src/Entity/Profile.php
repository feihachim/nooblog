<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
//use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProfileRepository::class)
 * @Vich\Uploadable
 * @UniqueEntity(fields={"pseudo"},message="Il y a déjà un pseudo avec ce profil")
 */
class Profile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @var string
     * 
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @Vich\UploadableField(mapping="profile_images",fileNameProperty="imageName")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \Datetime|null
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=User::class, mappedBy="profile", cascade={"persist", "remove"})
     *
     * @var User|null
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="profile", orphanRemoval=true)
     *
     * @var Collection<int, Post>
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user", orphanRemoval=true)
     *
     * @var Collection<int, Comment>
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="profile", orphanRemoval=true)
     *
     * @var Collection<int, Like>
     */
    private $likes;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile)
        {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if (null === $user && null !== $this->user)
        {
            $this->user->setProfile(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $user && $user->getProfile() !== $this)
        {
            $user->setProfile($this);
        }

        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post))
        {
            $this->posts[] = $post;
            $post->setProfile($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post))
        {
            // set the owning side to null (unless already changed)
            /*if ($post->getProfile() === $this)
            {
                $post->setProfile(null);
            }*/
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment))
        {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment))
        {
            // set the owning side to null (unless already changed)
            /*if ($comment->getUser() === $this)
            {
                $comment->setUser(null);
            }*/
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like))
        {
            $this->likes[] = $like;
            $like->setProfile($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like))
        {
            // set the owning side to null (unless already changed)
            /*if ($like->getProfile() === $this)
            {
                $like->setProfile(null);
            }*/
        }

        return $this;
    }

    /**
     * Return only the security relevant data
     *
     * @return array{'id':int,'pseudo':string}
     */
    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'pseudo' => $this->pseudo,

        ];
    }

    /**
     * Restore security relevant data
     *
     * @param array{'id':int,'pseudo':string} $data
     */
    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->pseudo = $data['pseudo'];
    }
}
