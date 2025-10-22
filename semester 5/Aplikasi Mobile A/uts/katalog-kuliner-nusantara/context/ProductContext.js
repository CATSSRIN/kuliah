// context/ProductContext.js
import React, { createContext, useState, useContext } from 'react';
import { initialProducts } from '../data/initialData';

// Buat Context
const ProductContext = createContext();

// Custom hook untuk menggunakan ProductContext
export const useProducts = () => {
  const context = useContext(ProductContext);
  if (!context) {
    throw new Error('useProducts must be used within ProductProvider');
  }
  return context;
};

// Provider Component
export const ProductProvider = ({ children }) => {
  const [products, setProducts] = useState(initialProducts);

  // CREATE: Tambah produk baru
  const addProduct = (newProduct) => {
    const productWithId = {
      ...newProduct,
      id: Date.now().toString(), // Generate ID unique
    };
    setProducts([...products, productWithId]);
  };

  // READ: Sudah ada di state products
  
  // UPDATE: Update produk
  const updateProduct = (id, updatedProduct) => {
    setProducts(
      products.map((product) =>
        product.id === id ? { ...product, ...updatedProduct } : product
      )
    );
  };

  // DELETE: Hapus produk
  const deleteProduct = (id) => {
    setProducts(products.filter((product) => product.id !== id));
  };

  // GET: Cari produk by ID
  const getProductById = (id) => {
    return products.find((product) => product.id === id);
  };

  const value = {
    products,
    addProduct,
    updateProduct,
    deleteProduct,
    getProductById,
  };

  return (
    <ProductContext.Provider value={value}>
      {children}
    </ProductContext.Provider>
  );
};
