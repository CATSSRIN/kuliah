import React, { useEffect, useState } from 'react'
import { View, Text, Image, TouchableOpacity, StyleSheet, ActivityIndicator, TextInput, FlatList } from 'react-native'

type Manga = { id: string; title: string; cover: string | null }

export default function MangaDexAdultScreen({ navigation }: any) {
  const [list, setList] = useState<Manga[]>([])
  const [display, setDisplay] = useState<Manga[]>([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState<string | null>(null)
  const [query, setQuery] = useState('')
  const [debounced, setDebounced] = useState('')
  const [batch, setBatch] = useState(1)
  const [hasMore, setHasMore] = useState(true)

  useEffect(() => {
    const t = setTimeout(() => {
      setDebounced(query)
      setList([]); setDisplay([]); setBatch(1)
    }, 500)
    return () => clearTimeout(t)
  }, [query])

  const fetchCover = async (mangaId: string, coverId: string) => {
    try {
      const res = await fetch(`https://api.mangadex.org/cover/${coverId}`)
      const data = await res.json()
      const file = data.data?.attributes?.fileName
      return file ? `https://uploads.mangadex.org/covers/${mangaId}/${file}.256.jpg` : null
    } catch { return null }
  }

  const fetchManga = async (title?: string) => {
    setLoading(true)
    try {
      const ratings = ['safe','suggestive','erotica','pornographic'].map(r=>`contentRating[]=${r}`).join('&')
      const url = title
        ? `https://api.mangadex.org/manga?title=${encodeURIComponent(title)}&limit=50&${ratings}`
        : `https://api.mangadex.org/manga?limit=50&${ratings}`

      const res = await fetch(url)
      const data = await res.json()
      if (!data?.data) { setError('Data kosong'); setLoading(false); return }

      const parsed: Manga[] = await Promise.all(data.data.map(async (item: any) => {
        const coverRel = item.relationships?.find((rel: any) => rel.type === 'cover_art')
        const coverUrl = coverRel?.id ? await fetchCover(item.id, coverRel.id) : null
        const titleObj = item.attributes?.title || {}
        const title = titleObj.en || Object.values(titleObj)[0] || 'No Title'
        return { id: item.id, title, cover: coverUrl }
      }))

      setList(parsed)
      setDisplay(parsed.slice(0,20))
      setHasMore(parsed.length > 20)
      setError(null)
    } catch { setError('Gagal ambil data') }
    finally { setLoading(false) }
  }

  useEffect(() => { fetchManga(debounced) }, [debounced])

  const loadMore = () => {
    const next = batch+1
    const newDisplay = list.slice(0,next*20)
    setDisplay(newDisplay); setBatch(next); setHasMore(newDisplay.length < list.length)
  }

  const renderItem = ({ item }: { item: Manga }) => (
    <TouchableOpacity style={styles.item} onPress={() => navigation.navigate('MangaReaderAdult',{ mangaId:item.id,title:item.title })}>
      {item.cover ? <Image source={{ uri:item.cover }} style={styles.img}/> : <Text style={styles.noCover}>No Cover</Text>}
      <Text style={styles.label}>{item.title}</Text>
    </TouchableOpacity>
  )

  return (
    <View style={styles.container}>
      <Text style={styles.title}>MangaDex Adult</Text>
      <TextInput style={styles.input} placeholder="Cari manga..." placeholderTextColor="#888" value={query} onChangeText={setQuery}/>
      {loading && <ActivityIndicator color="#a78bfa"/>}
      {error && <Text style={styles.error}>{error}</Text>}
      <FlatList data={display} keyExtractor={(i,idx)=>i.id+'-'+idx} renderItem={renderItem}
        ListFooterComponent={!loading && hasMore ? (
          <TouchableOpacity style={styles.more} onPress={loadMore}><Text style={styles.moreText}>Load more</Text></TouchableOpacity>
        ):null}/>
    </View>
  )
}

const styles = StyleSheet.create({
  container:{flex:1,backgroundColor:'#1e1b2e',padding:16},
  title:{color:'#fff',fontSize:18,fontWeight:'bold',marginBottom:12},
  input:{backgroundColor:'#2a2540',color:'#fff',padding:8,borderRadius:8,marginBottom:12},
  item:{marginBottom:16},
  img:{width:'100%',height:200,borderRadius:8,backgroundColor:'#2a2540'},
  noCover:{color:'#aaa',fontStyle:'italic',marginTop:8},
  label:{color:'#fff',fontSize:14,marginTop:4},
  error:{color:'red',marginTop:8},
  more:{backgroundColor:'#a78bfa',padding:12,borderRadius:8,alignItems:'center',marginVertical:12},
  moreText:{color:'#fff',fontWeight:'bold'}
})
