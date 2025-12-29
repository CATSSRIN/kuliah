import React, { useState, useCallback } from 'react';
import { View, Text, FlatList, TouchableOpacity, StyleSheet, Alert } from 'react-native';
import * as FileSystem from 'expo-file-system/legacy';
import { StackNavigationProp } from '@react-navigation/stack';
import { useFocusEffect } from '@react-navigation/native';

type RootStackParamList = {
  OfflineScreen: undefined;
  PageViewer: { pages: string[]; startIndex: number };
};

type Props = {
  navigation: StackNavigationProp<RootStackParamList, 'OfflineScreen'>;
};

type OfflineItem = {
  dirName: string;
  displayName: string;
  pageCount: number;
};

export default function OfflineScreen({ navigation }: Props) {
  const [offlineChapters, setOfflineChapters] = useState<OfflineItem[]>([]);
  const [refreshing, setRefreshing] = useState(false);

  const parseName = (folder: string): string => {
    if (folder.startsWith('manga-')) {
      const parts = folder.split('-');
      const mangaId = parts[1] || 'Unknown';
      const chapterId = parts[3] || 'Unknown';
      return `Manga ${mangaId} - Chapter ${chapterId}`;
    } else if (folder.startsWith('chapter-')) {
      return `Chapter ${folder.replace('chapter-', '')}`;
    }
    return folder;
  };

  const loadOffline = async () => {
    try {
      const dir = (FileSystem as any).documentDirectory || '';
      const files = await FileSystem.readDirectoryAsync(dir);

      const chapters: OfflineItem[] = [];
      for (const f of files) {
        if (f.startsWith('chapter-') || f.startsWith('manga-')) {
          const folderPath = dir + f + '/';
          const pageFiles = await FileSystem.readDirectoryAsync(folderPath);
          const pageCount = pageFiles.filter(
            (pf: string) => pf.endsWith('.jpg') || pf.endsWith('.png')
          ).length;

          chapters.push({
            dirName: f,
            displayName: parseName(f),
            pageCount,
          });
        }
      }

      chapters.sort((a, b) => a.displayName.localeCompare(b.displayName));
      setOfflineChapters(chapters);
    } catch (err) {
      console.error(err);
    }
  };

  useFocusEffect(
    useCallback(() => {
      loadOffline();
    }, [])
  );

  const onRefresh = async () => {
    setRefreshing(true);
    await loadOffline();
    setRefreshing(false);
  };

  const openChapter = async (chapterDir: string) => {
    try {
      const dir = (FileSystem as any).documentDirectory + chapterDir + '/';
      const files = await FileSystem.readDirectoryAsync(dir);

      const pages = files
        .filter((f: string) => f.endsWith('.jpg') || f.endsWith('.png'))
        .sort()
        .map((f: string) => dir + f);

      if (pages.length === 0) {
        Alert.alert('Error', 'Tidak ada halaman di folder offline ini.');
        return;
      }

      navigation.navigate('PageViewer', { pages, startIndex: 0 });
    } catch (err) {
      console.error(err);
      Alert.alert('Error', 'Gagal membuka chapter offline.');
    }
  };

  const deleteItem = async (chapterDir: string) => {
    Alert.alert('Confirm', `Hapus ${chapterDir}?`, [
      { text: 'Batal', style: 'cancel' },
      {
        text: 'Hapus',
        style: 'destructive',
        onPress: async () => {
          try {
            const dir = (FileSystem as any).documentDirectory || '';
            await FileSystem.deleteAsync(dir + chapterDir, { idempotent: true });
            setOfflineChapters(prev => prev.filter(ch => ch.dirName !== chapterDir));
            Alert.alert('Deleted', 'Chapter offline berhasil dihapus.');
          } catch (err) {
            console.error(err);
            Alert.alert('Error', 'Gagal hapus chapter.');
          }
        },
      },
    ]);
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Offline Chapters</Text>

      <FlatList
        data={offlineChapters}
        keyExtractor={(item) => item.dirName}
        renderItem={({ item }) => (
          <View style={styles.itemRow}>
            <TouchableOpacity style={styles.item} onPress={() => openChapter(item.dirName)}>
              <Text style={styles.itemText}>
                {item.displayName} ({item.pageCount} pages)
              </Text>
            </TouchableOpacity>
            <TouchableOpacity style={styles.deleteButton} onPress={() => deleteItem(item.dirName)}>
              <Text style={styles.deleteText}>🗑️</Text>
            </TouchableOpacity>
          </View>
        )}
        ListEmptyComponent={
          <Text style={{ color: '#aaa', textAlign: 'center', marginTop: 20 }}>
            Belum ada chapter offline
          </Text>
        }
        refreshing={refreshing}
        onRefresh={onRefresh}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#1e1b2e', padding: 12 },
  title: { color: '#fff', fontSize: 18, marginBottom: 12 },
  itemRow: { flexDirection: 'row', alignItems: 'center', marginBottom: 8 },
  item: { flex: 1, padding: 12, backgroundColor: '#333', borderRadius: 6 },
  itemText: { color: '#fff' },
  deleteButton: {
    marginLeft: 8,
    padding: 12,
    backgroundColor: '#ef4444',
    borderRadius: 6,
    alignItems: 'center',
    justifyContent: 'center',
  },
  deleteText: { color: '#fff', fontWeight: 'bold' },
});
