import React, { useState } from 'react';
import { View, Text, TextInput, TouchableOpacity, StyleSheet, Alert } from 'react-native';
import * as FileSystem from 'expo-file-system/legacy';
import { useRoute, useNavigation } from '@react-navigation/native';

export default function OfflineSaveScreen() {
  const route = useRoute<any>();
  const navigation = useNavigation<any>();
  const { pages = [], mangaId, chapterId } = route.params || {};

  const [title, setTitle] = useState('');
  const [chapter, setChapter] = useState('');

  const saveOffline = async () => {
    try {
      const mangaKey = title.trim() || mangaId || 'unknownManga';
      const chapterKey = chapter.trim() || chapterId || 'unknownChapter';
      const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
      const dir =
        (FileSystem as any).documentDirectory +
        `manga-${mangaKey}-chapter-${chapterKey}-${timestamp}/`;

      await FileSystem.makeDirectoryAsync(dir, { intermediates: true });

      for (let i = 0; i < pages.length; i++) {
        const url = pages[i];
        const localPath = dir + `page-${i}.jpg`;
        await FileSystem.downloadAsync(url, localPath);
      }

      Alert.alert('Berhasil', `Chapter ${chapterKey} dari Manga ${mangaKey} berhasil disimpan offline`);
      navigation.goBack();
    } catch (err) {
      console.error(err);
      Alert.alert('Error', 'Failed to save offline');
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.label}>Nama Manga</Text>
      <TextInput
        style={styles.input}
        placeholder="Masukkan nama manga"
        placeholderTextColor="#888"
        value={title}
        onChangeText={setTitle}
      />

      <Text style={styles.label}>Chapter</Text>
      <TextInput
        style={styles.input}
        placeholder="Masukkan chapter"
        placeholderTextColor="#888"
        value={chapter}
        onChangeText={setChapter}
      />

      <TouchableOpacity style={styles.button} onPress={saveOffline}>
        <Text style={styles.buttonText}>💾 Simpan Offline</Text>
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
