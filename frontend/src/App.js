import React, { useEffect, useState } from "react";
import axios from "./api/axios";

function App() {
  const [categories, setCategories] = useState([]);
  const [produits, setProduits] = useState([]);
  const [newCategory, setNewCategory] = useState("");
  const [newProduit, setNewProduit] = useState({
    nom: "",
    description: "",
    prix: "",
    dateCreation: "",  
    categorieId: "",   
  });

  useEffect(() => {
    // Récupérer les catégories
    axios.get("/categories").then((response) => {
      setCategories(response.data);
    });

    // Récupérer les produits
    axios.get("/produits").then((response) => {
      setProduits(response.data);
    });
  }, []);

  // Ajouter une catégorie
  const addCategory = () => {
    if (newCategory.trim() === "") return;

    axios
      .post("/categories", { nom: newCategory })
      .then((response) => {
        setCategories([...categories, response.data]);
        setNewCategory("");
      })
      .catch((error) => console.error("Erreur lors de l'ajout de la catégorie", error));
  };

  const addProduit = () => {
    console.log("Données envoyées avant l'envoi :", newProduit);
  
    // Validation des champs
    if (!newProduit.nom.trim() || !newProduit.description.trim() || !newProduit.prix.trim()) {
      alert("Tous les champs doivent être remplis !");
      return;
    }
  
    // Vérification du format du prix
    const parsedPrix = parseFloat(newProduit.prix);
    if (isNaN(parsedPrix) || parsedPrix <= 0) {
      alert("Le prix doit être un nombre positif !");
      return;
    }
  
    // Ajouter la catégorie et la date de création
    const produitToSend = {
      ...newProduit,
      prix: parsedPrix,
      categorie: 3, 
      dateCreation: new Date().toISOString().split('T')[0], // Date au format YYYY-MM-DD
    };
  
    console.log("Produit prêt à être envoyé :", produitToSend);
  
    // Envoi des données à l'API
    axios
      .post("/produits", produitToSend)
      .then((response) => {
        console.log("Produit ajouté :", response.data);
        setProduits((prevProduits) => [...prevProduits, response.data]);
        setNewProduit({ nom: "", description: "", prix: "" });
        alert("Produit ajouté avec succès !");
      })
      .catch((error) => {
        console.error("Erreur lors de l'ajout du produit :", error);
        if (error.response) {
          console.error("Détails de l'erreur :", error.response.data);
          alert(`Erreur: ${error.response.data.message}`);
        } else {
          alert("Erreur inconnue du serveur.");
        }
      });
  };
  

  // Supprimer une catégorie
  const deleteCategory = (id) => {
    axios
      .delete(`/categories/${id}`)
      .then(() => {
        setCategories(categories.filter((categorie) => categorie.id !== id));
      })
      .catch((error) => console.error("Erreur lors de la suppression de la catégorie", error));
  };

  // Supprimer un produit
  const deleteProduit = (id) => {
    axios
      .delete(`/produits/${id}`)
      .then(() => {
        setProduits(produits.filter((produit) => produit.id !== id));
      })
      .catch((error) => console.error("Erreur lors de la suppression du produit", error));
  };

  return (
    <div className="container mx-auto p-4">
      {/* Ajouter un produit */}
      <div className="my-4">
        <h2 className="text-2xl font-bold">Ajouter un Produit</h2>
        <input
          type="text"
          placeholder="Nom"
          className="border p-2"
          value={newProduit.nom}
          onChange={(e) => setNewProduit({ ...newProduit, nom: e.target.value })}
        />
        <input
          type="text"
          placeholder="Description"
          className="border p-2 ml-2"
          value={newProduit.description}
          onChange={(e) => setNewProduit({ ...newProduit, description: e.target.value })}
        />
        <input
          type="text"
          placeholder="Prix"
          className="border p-2 ml-2"
          value={newProduit.prix}
          onChange={(e) => setNewProduit({ ...newProduit, prix: e.target.value })}
        />
        
        {/* Champ pour choisir une catégorie */}
        <select
          value={newProduit.categorieId}
          onChange={(e) => setNewProduit({ ...newProduit, categorieId: e.target.value })}
          className="border p-2 ml-2"
        >
          <option value="">Choisir une catégorie</option>
          {categories.map((categorie) => (
            <option key={categorie.id} value={categorie.id}>
              {categorie.nom}
            </option>
          ))}
        </select>

        {/* Date de création */}
        <input
          type="date"
          className="border p-2 ml-2"
          value={newProduit.dateCreation.split("T")[0]} 
          onChange={(e) => setNewProduit({ ...newProduit, dateCreation: e.target.value })}
        />

        <button className="bg-blue-500 text-white p-2 ml-2" onClick={addProduit}>
          Ajouter
        </button>
      </div>

      {/* Affichage des produits */}
      <h1 className="text-2xl font-bold">Liste des Produits</h1>
      <table className="min-w-full bg-white border">
        <thead>
          <tr>
            <th className="py-2 px-4 border">Nom</th>
            <th className="py-2 px-4 border">Description</th>
            <th className="py-2 px-4 border">Prix</th>
            <th className="py-2 px-4 border">Catégorie</th>
            <th className="py-2 px-4 border">Date de création</th>
            <th className="py-2 px-4 border">Action</th>
          </tr>
        </thead>
        <tbody>
          {produits.map((produit) => (
            <tr key={produit.id}>
              <td className="py-2 px-4 border">{produit.nom}</td>
              <td className="py-2 px-4 border">{produit.description}</td>
              <td className="py-2 px-4 border">{produit.prix}€</td>
              <td className="py-2 px-4 border">
                {categories.find((cat) => cat.id === produit.categorieId)?.nom}
              </td>
              <td className="py-2 px-4 border">{produit.dateCreation.split("T")[0]}</td>
              <td className="py-2 px-4 border">
                <button
                  className="bg-red-500 text-white p-1"
                  onClick={() => deleteProduit(produit.id)}
                >
                  Supprimer
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Ajouter une catégorie */}
      <div className="my-4">
        <h2 className="text-2xl font-bold">Ajouter une Catégorie</h2>
        <input
          type="text"
          placeholder="Nom de la catégorie"
          className="border p-2"
          value={newCategory}
          onChange={(e) => setNewCategory(e.target.value)}
        />
        <button className="bg-blue-500 text-white p-2 ml-2" onClick={addCategory}>
          Ajouter
        </button>
      </div>

      {/* Affichage des catégories */}
      <h2 className="text-2xl font-bold mt-8">Liste des Catégories</h2>
      <ul className="list-disc pl-4">
        {categories.map((categorie) => (
          <li key={categorie.id}>
            {categorie.nom}
            <button
              className="bg-red-500 text-white p-1 ml-2"
              onClick={() => deleteCategory(categorie.id)}
            >
              Supprimer
            </button>
          </li>
        ))}
      </ul>
    </div>
  );
}

export default App;
