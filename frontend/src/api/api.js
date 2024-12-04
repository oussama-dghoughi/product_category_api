import axios from 'axios';

const API_URL = 'http://localhost:8000'; 

export const getCategories = () => axios.get(`${API_URL}/categories`);
export const createCategory = (data) => axios.post(`${API_URL}/categories`, data);
export const updateCategory = (id, data) => axios.put(`${API_URL}/categories/${id}`, data);
export const deleteCategory = (id) => axios.delete(`${API_URL}/categories/${id}`);

export const getProducts = () => axios.get(`${API_URL}/produits`);
export const createProduct = (data) => axios.post(`${API_URL}/produits`, data);
export const updateProduct = (id, data) => axios.put(`${API_URL}/produits/${id}`, data);
export const deleteProduct = (id) => axios.delete(`${API_URL}/produits/${id}`);
