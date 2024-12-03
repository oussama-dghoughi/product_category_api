import axios from 'axios';

const instance = axios.create({
  baseURL: 'http://localhost:8000', // Remplacez par votre URL de base
});

export default instance;
