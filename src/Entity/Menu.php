<?php

class Menu
{
    // Je déclare les informations qu'un menu doit contenir
    private int $id;
    private string $titre;
    private string $description;
    private int $nombrePersonneMin;
    private float $prixMin;
    private ?string $imageUrl;
    private ?string $imageAlt;

    // Quand je crée un Menu, je remplis ses propriétés avec les valeurs reçues
    public function __construct(
        int $id,
        string $titre,
        string $description,
        int $nombrePersonneMin,
        float $prixMin,
        ?string $imageUrl,
        ?string $imageAlt
    ) {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->nombrePersonneMin = $nombrePersonneMin;
        $this->prixMin = $prixMin;
        $this->imageUrl = $imageUrl;
        $this->imageAlt = $imageAlt;
    }

    // Je transforme mon objet Menu en tableau
    // pour pouvoir ensuite l'envoyer en JSON au JavaScript
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'nombre_personne_min' => $this->nombrePersonneMin,
            'prix_min' => $this->prixMin,
            'image_url' => $this->imageUrl,
            'image_alt' => $this->imageAlt,
        ];
    }
}