import React, { useEffect, useState } from "react";
import axios from "./api/axios";

function App() {
  const [categories, setCategories] = useState([]);
  const [produits, setProduits] = useState([]);

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

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold">Liste des Produits</h1>
      <table className="min-w-full bg-white border">
        <thead>
          <tr>
            <th className="py-2 px-4 border">Nom</th>
            <th className="py-2 px-4 border">Description</th>
            <th className="py-2 px-4 border">Prix</th>
          </tr>
        </thead>
        <tbody>
          {produits.map((produit) => (
            <tr key={produit.id}>
              <td className="py-2 px-4 border">{produit.nom}</td>
              <td className="py-2 px-4 border">{produit.description}</td>
              <td className="py-2 px-4 border">{produit.prix}€</td>
            </tr>
          ))}
        </tbody>
      </table>

      <h2 className="text-2xl font-bold mt-8">Liste des Catégories</h2>
      <ul className="list-disc pl-4">
        {categories.map((categorie) => (
          <li key={categorie.id}>{categorie.nom}</li>
        ))}
      </ul>
    </div>
  );
}

export default App;
