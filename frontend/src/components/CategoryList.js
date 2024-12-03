import React, { useState, useEffect } from 'react';
import { getCategories, createCategory, updateCategory, deleteCategory } from '../api/api';
import CategoryForm from './CategoryForm';

const CategoryList = () => {
  const [categories, setCategories] = useState([]);
  const [showForm, setShowForm] = useState(false);
  const [editingCategory, setEditingCategory] = useState(null);

  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    const response = await getCategories();
    setCategories(response.data);
  };

  const handleAddCategory = async (category) => {
    if (editingCategory) {
      await updateCategory(editingCategory.id, category);
    } else {
      await createCategory(category);
    }
    loadCategories();
    setShowForm(false);
    setEditingCategory(null);
  };

  const handleDeleteCategory = async (id) => {
    await deleteCategory(id);
    loadCategories();
  };

  const handleEditCategory = (category) => {
    setShowForm(true);
    setEditingCategory(category);
  };

  return (
    <div className="p-4">
      <h1 className="text-xl font-bold">Catégories</h1>
      <button onClick={() => setShowForm(!showForm)} className="my-4 px-4 py-2 bg-green-500 text-white rounded">
        {showForm ? 'Annuler' : 'Ajouter une catégorie'}
      </button>

      {showForm && <CategoryForm onSubmit={handleAddCategory} initialData={editingCategory} />}

      <ul className="mt-4 space-y-2">
        {categories.map((category) => (
          <li key={category.id} className="p-2 bg-gray-200 rounded flex justify-between items-center">
            {category.nom}
            <div>
              <button onClick={() => handleEditCategory(category)} className="px-2 py-1 bg-yellow-500 text-white rounded">
                Modifier
              </button>
              <button onClick={() => handleDeleteCategory(category.id)} className="ml-2 px-2 py-1 bg-red-500 text-white rounded">
                Supprimer
              </button>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};

export default CategoryList;
