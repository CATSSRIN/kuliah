import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  ActivityIndicator,
  Image,
  StyleSheet,
  FlatList,
  TouchableOpacity,
} from 'react-native';
import { Picker } from '@react-native-picker/picker';
import { StackNavigationProp } from '@react-navigation/stack';
import { RouteProp } from '@react-navigation/native';

type RootStackParamList = {
  MangaReader: { mangaId: string; title: string };
  PageViewer: { pages: string[]; startIndex: number };
};

type MangaReaderNavigationProp = StackNavigationProp<RootStackParamList, 'MangaReader'>;
type MangaReaderRouteProp = RouteProp<RootStackParamList, 'MangaReader'>;

type Props = {
  navigation: MangaReaderNavigationProp;
  route: MangaReaderRouteProp;
};

export default function MangaReader({ route, navigation }: Props) {
  const { mangaId, title } = route.params;
  const [chapters, setChapters] = useState<any[]>([]);
  const [selectedChapter, setSelectedChapter] = useState<string | null>(null);
  const [pages, setPages] = useState<string[]>([]);
  const [displayPages, setDisplayPages] = useState<string[]>([]);
  const [batchIndex, setBatchIndex] = useState(1);
  const [loadingPages, setLoadingPages] = useState(false);

  const fetchChapters = async () => {
    try {
      const res = await fetch(
        `https://api.mangadex.org/chapter?manga=${mangaId}&limit=20&translatedLanguage[]=en`
      );
      const data = await res.json();
      setChapters(data.data || []);
      if (data.data?.length > 0) {
        setSelectedChapter(data.data[0].id);
      }
    } catch (err) {
      console.error('Error fetch chapters:', err);
    }
  };

  const fetchPages = async (chapterId: string) => {
    setLoadingPages(true);
    try {
      const res = await fetch(`https://api.mangadex.org/at-home/server/${chapterId}`);
      const data = await res.json();
      const imgs: string[] = data.chapter?.data?.map(
        (fileName: string) => `${data.baseUrl}/data/${data.chapter.hash}/${fileName}`
      ) || [];
      setPages(imgs);
      setDisplayPages(imgs.slice(0, 20));
      setBatchIndex(1);
    } catch (err) {
      console.error('Error fetch pages:', err);
    } finally {
      setLoadingPages(false);
    }
  };

  useEffect(() => {
    fetchChapters();
  }, []);

  useEffect(() => {
    if (selectedChapter) {
      fetchPages(selectedChapter);
    }
  }, [selectedChapter]);

  const loadMore = () => {
    const nextBatch = batchIndex + 1;
    const newDisplay = pages.slice(0, nextBatch * 20);
    setDisplayPages(newDisplay);
    setBatchIndex(nextBatch);
  };

  const renderItem = ({ item, index }: { item: string; index: number }) => (
    <TouchableOpacity
      onPress={() => navigation.navigate('PageViewer', { pages, startIndex: index })}
    >
      <Image
        source={{ uri: item }}
        style={styles.page}
        resizeMode="contain"
        onError={() => console.log('Image gagal dimuat:', index)}
      />
      <Text style={styles.pageLabel}>Page {index + 1}</Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.title}>{title}</Text>

      {chapters.length > 0 && (
        <Picker
          selectedValue={selectedChapter}
          style={styles.picker}
          onValueChange={(itemValue) => setSelectedChapter(itemValue)}
        >
          {chapters.map((ch) => (
            <Picker.Item
              key={ch.id}
              label={`Chapter ${ch.attributes.chapter ?? 'N/A'}`}
              value={ch.id}
              color="#000"
            />
          ))}
        </Picker>
      )}

      {loadingPages && (
        <View style={styles.center}>
          <ActivityIndicator color="#a78bfa" />
          <Text style={styles.loadingText}>Loading pages…</Text>
        </View>
      )}

      {!loadingPages && displayPages.length > 0 && (
        <FlatList
          data={displayPages}
          keyExtractor={(_, index) => index.toString()}
          renderItem={renderItem}
          contentContainerStyle={{ paddingBottom: 20 }}
          ListFooterComponent={
            displayPages.length < pages.length ? (
              <TouchableOpacity style={styles.loadMoreButton} onPress={loadMore}>
                <Text style={styles.loadMoreText}>Load more</Text>
              </TouchableOpacity>
            ) : null
          }
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#1e1b2e' },
  title: { color: '#fff', fontSize: 18, fontWeight: 'bold', margin: 12 },
  picker: { backgroundColor: '#eee', marginHorizontal: 12 },
  page: { width: '100%', height: 500, marginBottom: 12, backgroundColor: '#000' },
  pageLabel: { color: '#aaa', textAlign: 'center', marginBottom: 8 },
  center: { justifyContent: 'center', alignItems: 'center', marginTop: 20 },
  loadingText: { color: '#fff', marginTop: 8 },
  loadMoreButton: {
    backgroundColor: '#a78bfa',
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
    marginVertical: 12,
  },
  loadMoreText: { color: '#fff', fontWeight: 'bold' },
});
