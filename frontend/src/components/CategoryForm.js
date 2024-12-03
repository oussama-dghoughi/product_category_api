import React, { useState, useEffect } from 'react';

const CategoryForm = ({ onSubmit, initialData = {} }) => {
  const [name, setName] = useState(initialData.nom || '');

  useEffect(() => {
    setName(initialData.nom || '');  // Met à jour si une catégorie est modifiée
  }, [initialData]);

  const handleSubmit = (e) => {
    e.preventDefault();
    onSubmit({ nom: name });
  };

  return (
    <form onSubmit={handleSubmit} className="p-4 bg-gray-100 rounded">
      <div>
        <label htmlFor="name" className="block text-sm font-medium">Nom de la catégorie</label>
        <input
          id="name"
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          className="mt-1 block w-full p-2 border rounded"
          required
        />
      </div>
      <button type="submit" className="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Enregistrer</button>
    </form>
  );
};

export default CategoryForm;
