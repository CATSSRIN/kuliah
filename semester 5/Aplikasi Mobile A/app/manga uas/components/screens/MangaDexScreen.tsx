import React, { useEffect, useState } from 'react';
import {
  View,
  Text,
  Image,
  TouchableOpacity,
  StyleSheet,
  ActivityIndicator,
  TextInput,
  FlatList,
} from 'react-native';

type Manga = {
  id: string;
  title: string;
  cover: string | null;
};

export default function MangaDexScreen({ navigation }: any) {
  const [mangaList, setMangaList] = useState<Manga[]>([]);
  const [displayList, setDisplayList] = useState<Manga[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [query, setQuery] = useState('');
  const [debouncedQuery, setDebouncedQuery] = useState('');
  const [offset, setOffset] = useState(0);
  const [hasMore, setHasMore] = useState(true);
  const [batchIndex, setBatchIndex] = useState(1);

  useEffect(() => {
    const handler = setTimeout(() => {
      setDebouncedQuery(query);
      setOffset(0);
      setMangaList([]);
      setDisplayList([]);
      setBatchIndex(1);
    }, 500);
    return () => clearTimeout(handler);
  }, [query]);

  const fetchCover = async (mangaId: string, coverId: string): Promise<string | null> => {
    try {
      const res = await fetch(`https://api.mangadex.org/cover/${coverId}`, {
        headers: { Accept: 'application/json' },
      });
      const data = await res.json();
      const fileName = data.data?.attributes?.fileName;
      return fileName
        ? `https://uploads.mangadex.org/covers/${mangaId}/${fileName}.256.jpg`
        : null;
    } catch {
      return null;
    }
  };

  const fetchManga = async (searchTitle?: string, offsetValue: number = 0) => {
    setLoading(true);
    try {
      const baseUrl = 'https://api.mangadex.org/manga';
      const url = searchTitle
        ? `${baseUrl}?title=${encodeURIComponent(searchTitle)}&limit=50&offset=${offsetValue}`
        : `${baseUrl}?limit=50&offset=${offsetValue}`;

      const res = await fetch(url, { headers: { Accept: 'application/json' } });
      const data = await res.json();

      if (!data || !data.data) {
        setError('Data kosong dari MangaDex');
        setLoading(false);
        return;
      }

      const parsed: Manga[] = await Promise.all(
        data.data.map(async (item: any) => {
          try {
            const coverRel = item.relationships?.find((rel: any) => rel.type === 'cover_art');
            const coverUrl = coverRel?.id ? await fetchCover(item.id, coverRel.id) : null;

            const titleObj = item.attributes?.title || {};
            const title =
              titleObj.en ||
              titleObj['en-us'] ||
              titleObj['ja'] ||
              titleObj['jp-ro'] ||
              Object.values(titleObj)[0] ||
              'No Title';

            return {
              id: item.id,
              title,
              cover: coverUrl,
            };
          } catch {
            return { id: item.id, title: 'No Title', cover: null };
          }
        })
      );

      setMangaList(parsed);
      setDisplayList(parsed.slice(0, 20));
      setBatchIndex(1);
      setHasMore(parsed.length > 20);
      setError(null);
    } catch (err) {
      console.error('Fetch MangaDex error:', err);
      setError('Gagal mengambil data dari MangaDex');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchManga(debouncedQuery, 0);
  }, [debouncedQuery]);

  const loadMoreBatch = () => {
    const nextBatch = batchIndex + 1;
    const newDisplay = mangaList.slice(0, nextBatch * 20);
    setDisplayList(newDisplay);
    setBatchIndex(nextBatch);
    setHasMore(newDisplay.length < mangaList.length);
  };

  const renderItem = ({ item }: { item: Manga }) => (
    <TouchableOpacity
      style={styles.mangaItem}
      onPress={() =>
        navigation.navigate('MangaReader', {
          mangaId: item.id,
          title: item.title,
        })
      }
    >
      {item.cover ? (
        <Image source={{ uri: item.cover }} style={styles.mangaImage} />
      ) : (
        <Text style={styles.noCoverText}>No Cover Available</Text>
      )}
      <Text style={styles.mangaLabel}>{item.title}</Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <Text style={styles.sectionTitle}>MangaDex Online</Text>

      <View style={styles.searchContainer}>
        <TextInput
          style={styles.searchInput}
          placeholder="Cari manga..."
          placeholderTextColor="#888"
          value={query}
          onChangeText={setQuery}
        />
      </View>

      {loading && (
        <View style={styles.center}>
          <ActivityIndicator color="#a78bfa" />
          <Text style={styles.loadingText}>Searching…</Text>
        </View>
      )}

      {error && (
        <View style={styles.center}>
          <Text style={styles.errorText}>{error}</Text>
        </View>
      )}

      {!loading && !error && displayList.length === 0 && (
        <View style={styles.center}>
          <Text style={styles.noResultText}>No results found</Text>
        </View>
      )}

      <FlatList
        data={displayList}
        keyExtractor={(item, index) => item.id + '-' + index}
        renderItem={renderItem}
        contentContainerStyle={{ paddingBottom: 20 }}
        ListFooterComponent={
          !loading && hasMore ? (
            <TouchableOpacity style={styles.loadMoreButton} onPress={loadMoreBatch}>
              <Text style={styles.loadMoreText}>Load more</Text>
            </TouchableOpacity>
          ) : null
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#1e1b2e', padding: 16 },
  sectionTitle: { color: '#fff', fontSize: 18, fontWeight: 'bold', marginBottom: 12 },
  searchContainer: { marginBottom: 12 },
  searchInput: {
    backgroundColor: '#2a2540',
    color: '#fff',
    padding: 8,
    borderRadius: 8,
  },
  mangaItem: { marginBottom: 16 },
  mangaImage: { width: '100%', height: 200, borderRadius: 8, backgroundColor: '#2a2540' },
  noCoverText: { color: '#aaa', fontStyle: 'italic', marginTop: 8 },
  mangaLabel: { color: '#fff', fontSize: 14, marginTop: 4 },
  center: { justifyContent: 'center', alignItems: 'center' },
  loadingText: { color: '#fff', marginTop: 8 },
  errorText: { color: 'red' },
  noResultText: { color: '#fff', marginTop: 8, fontStyle: 'italic' },
  loadMoreButton: {
    backgroundColor: '#a78bfa',
    padding: 12,
    borderRadius: 8,
    alignItems: 'center',
    marginVertical: 12,
  },
  loadMoreText: { color: '#fff', fontWeight: 'bold' },
});
