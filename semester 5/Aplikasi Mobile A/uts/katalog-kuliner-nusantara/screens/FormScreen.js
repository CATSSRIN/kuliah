// screens/FormScreen.js
import React, { useState, useEffect } from 'react';
import {
  View,
  Text,
  TextInput,
  StyleSheet,
  TouchableOpacity,
  ScrollView,
  Alert,
} from 'react-native';
import { useProducts } from '../context/ProductContext';

export default function FormScreen({ route, navigation }) {
  const { productId, mode } = route.params || {};
  
  // Ambil fungsi dari Context
  const { getProductById, addProduct, updateProduct } = useProducts();
  
  // State untuk form fields
  const [name, setName] = useState('');
  const [category, setCategory] = useState('');
  const [price, setPrice] = useState('');
  const [description, setDescription] = useState('');
  const [origin, setOrigin] = useState('');
  const [image, setImage] = useState('');

  // Cek apakah mode edit
  const isEditMode = mode === 'edit' && productId;

  useEffect(() => {
    if (isEditMode) {
      // Load data produk jika edit mode
      const product = getProductById(productId);
      if (product) {
        setName(product.name);
        setCategory(product.category);
        setPrice(product.price.toString());
        setDescription(product.description);
        setOrigin(product.origin);
        setImage(product.image);
      }
    }
  }, [isEditMode, productId]);

  // CREATE: Fungsi untuk menambah produk baru
  const handleCreate = () => {
    if (!name || !category || !price || !description || !origin) {
      Alert.alert('Error', 'Semua field harus diisi!');
      return;
    }

    const newProduct = {
      name: name.trim(),
      category: category.trim(),
      price: parseInt(price),
      description: description.trim(),
      origin: origin.trim(),
      image: image.trim() || '🍽️',
    };

    // Panggil fungsi addProduct dari Context
    addProduct(newProduct);

    Alert.alert('Sukses', 'Produk berhasil ditambahkan!', [
      { 
        text: 'OK', 
        onPress: () => {
          navigation.goBack();
        }
      }
    ]);
  };

  // UPDATE: Fungsi untuk update produk
  const handleUpdate = () => {
    if (!name || !category || !price || !description || !origin) {
      Alert.alert('Error', 'Semua field harus diisi!');
      return;
    }

    const updatedData = {
      name: name.trim(),
      category: category.trim(),
      price: parseInt(price),
      description: description.trim(),
      origin: origin.trim(),
      image: image.trim() || '🍽️',
    };

    // Panggil fungsi updateProduct dari Context
    updateProduct(productId, updatedData);

    Alert.alert('Sukses', 'Produk berhasil diupdate!', [
      { 
        text: 'OK', 
        onPress: () => {
          navigation.goBack();
        }
      }
    ]);
  };

  const handleSubmit = () => {
    if (isEditMode) {
      handleUpdate();
    } else {
      handleCreate();
    }
  };

  return (
    <ScrollView style={styles.container}>
      <View style={styles.formContainer}>
        <Text style={styles.title}>
          {isEditMode ? '✏️ Edit Produk' : '➕ Tambah Produk Baru'}
        </Text>

        {/* Input Nama */}
        <Text style={styles.label}>Nama Produk *</Text>
        <TextInput
          style={styles.input}
          value={name}
          onChangeText={setName}
          placeholder="Contoh: Rendang"
          placeholderTextColor="#999"
        />

        {/* Input Kategori */}
        <Text style={styles.label}>Kategori *</Text>
        <TextInput
          style={styles.input}
          value={category}
          onChangeText={setCategory}
          placeholder="Contoh: Makanan Utama"
          placeholderTextColor="#999"
        />

        {/* Input Harga */}
        <Text style={styles.label}>Harga (Rp) *</Text>
        <TextInput
          style={styles.input}
          value={price}
          onChangeText={setPrice}
          placeholder="Contoh: 25000"
          keyboardType="numeric"
          placeholderTextColor="#999"
        />

        {/* Input Asal Daerah */}
        <Text style={styles.label}>Asal Daerah *</Text>
        <TextInput
          style={styles.input}
          value={origin}
          onChangeText={setOrigin}
          placeholder="Contoh: Sumatera Barat"
          placeholderTextColor="#999"
        />

        {/* Input Emoji/Icon */}
        <Text style={styles.label}>Icon Emoji (Opsional)</Text>
        <TextInput
          style={styles.input}
          value={image}
          onChangeText={setImage}
          placeholder="Contoh: 🍖 (default: 🍽️)"
          placeholderTextColor="#999"
        />

        {/* Input Deskripsi */}
        <Text style={styles.label}>Deskripsi *</Text>
        <TextInput
          style={[styles.input, styles.textArea]}
          value={description}
          onChangeText={setDescription}
          placeholder="Masukkan deskripsi produk"
          multiline
          numberOfLines={4}
          placeholderTextColor="#999"
        />

        {/* Preview */}
        {name && (
          <View style={styles.previewContainer}>
            <Text style={styles.previewLabel}>Preview:</Text>
            <View style={styles.previewCard}>
              <Text style={styles.previewEmoji}>{image || '🍽️'}</Text>
              <Text style={styles.previewName}>{name}</Text>
            </View>
          </View>
        )}

        {/* Tombol Submit */}
        <TouchableOpacity 
          style={styles.submitButton}
          onPress={handleSubmit}
        >
          <Text style={styles.submitButtonText}>
            {isEditMode ? '💾 Update Produk' : '➕ Tambah Produk'}
          </Text>
        </TouchableOpacity>

        {/* Tombol Batal */}
        <TouchableOpacity 
          style={styles.cancelButton}
          onPress={() => navigation.goBack()}
        >
          <Text style={styles.cancelButtonText}>❌ Batal</Text>
        </TouchableOpacity>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  formContainer: {
    padding: 20,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 20,
    textAlign: 'center',
  },
  label: {
    fontSize: 16,
    fontWeight: '600',
    color: '#555',
    marginTop: 12,
    marginBottom: 6,
  },
  input: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ddd',
    borderRadius: 8,
    padding: 12,
    fontSize: 16,
    color: '#333',
  },
  textArea: {
    height: 100,
    textAlignVertical: 'top',
  },
  previewContainer: {
    marginTop: 20,
    padding: 15,
    backgroundColor: '#fff',
    borderRadius: 8,
    borderWidth: 1,
    borderColor: '#ddd',
  },
  previewLabel: {
    fontSize: 14,
    fontWeight: '600',
    color: '#666',
    marginBottom: 10,
  },
  previewCard: {
    alignItems: 'center',
  },
  previewEmoji: {
    fontSize: 40,
    marginBottom: 8,
  },
  previewName: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#333',
  },
  submitButton: {
    backgroundColor: '#27ae60',
    paddingVertical: 14,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 24,
  },
  submitButtonText: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
  },
  cancelButton: {
    backgroundColor: '#95a5a6',
    paddingVertical: 14,
    borderRadius: 8,
    alignItems: 'center',
    marginTop: 12,
    marginBottom: 20,
  },
  cancelButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: 'bold',
  },
});
