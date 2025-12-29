import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useRoute, useNavigation } from '@react-navigation/native';

export default function BookmarkSaveScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const { pages = [], mangaId, chapterId, title, cover } = route.params || {};

  const [customTitle, setCustomTitle] = useState(title ?? '');
  const [customChapter, setCustomChapter] = useState(chapterId ?? '');

  const saveBookmark = async () => {
    try {
      const bookmarkItem = {
        mangaId,
        chapterId: customChapter.trim() || chapterId,
        title: customTitle.trim() || title || 'Untitled',
        cover: cover || pages[0],
        pages,
        savedAt: Date.now(),
      };

      const existing = await AsyncStorage.getItem('bookmarks');
      const bookmarks = existing ? JSON.parse(existing) : [];

      bookmarks.push(bookmarkItem);

      await AsyncStorage.setItem('bookmarks', JSON.stringify(bookmarks));
      Alert.alert('Berhasil', 'Bookmark disimpan dengan nama custom');
      navigation.goBack();
    } catch (err) {
      Alert.alert('Error', 'Gagal menyimpan bookmark');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>Nama Manga</Text>
      <TextInput
        style={styles.input}
        placeholder="Masukkan nama manga"
        placeholderTextColor="#888"
        value={customTitle}
        onChangeText={setCustomTitle}
      />

      <Text style={styles.label}>Chapter</Text>
      <TextInput
        style={styles.input}
        placeholder="Masukkan chapter"
        placeholderTextColor="#888"
        value={customChapter}
        onChangeText={setCustomChapter}
      />

      <TouchableOpacity style={styles.button} onPress={saveBookmark}>
        <Text style={styles.buttonText}>⭐ Simpan Bookmark</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#0f0e19', padding: 16 },
  label: { color: '#fff', marginBottom: 4 },
  input: {
    backgroundColor: '#1e1b2e',
    color: '#fff',
    padding: 10,
    borderRadius: 6,
    marginBottom: 12,
  },
  button: {
    backgroundColor: '#a78bfa',
    padding: 12,
    borderRadius: 6,
    alignItems: 'center',
  },
  buttonText: { color: '#fff', fontWeight: 'bold' },
});
