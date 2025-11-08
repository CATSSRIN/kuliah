import React, { useState } from 'react';
import {
  View,
  Text,
  StatusBar,
  Image,
  TouchableOpacity,
  Switch,
  TextInput,
  ScrollView,
  FlatList,
  StyleSheet,
  Linking,
  Alert,
  Platform,
} from 'react-native';

export default function App() {
  const [header, setHeader] = useState('Home');
  const [switchV, setSwitchV] = useState(true);
  const [username, setUsername] = useState('');
  const dataMahasiswa = [
    { namaMhs: 'Sarah', npmMhs: '13650027' },
    { namaMhs: 'Dion', npmMhs: '13650097' },
    { namaMhs: 'Virza', npmMhs: '13650077' },
    { namaMhs: 'Dion', npmMhs: '13650197' },
    { namaMhs: 'Virza', npmMhs: '13650277' },
  ];

  // Toast substitute for web/ios
  const showToast = (msg) => {
    if (Platform.OS === 'android') {
      // On Android, ToastAndroid works! On Snack web/ios, use alert instead
      import('react-native').then(({ ToastAndroid }) =>
        ToastAndroid?.show(msg, ToastAndroid.SHORT)
      );
    } else {
      alert(msg);
    }
  };

  return (
    <ScrollView style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#09bd75" />
      {/* Header */}
      <View style={styles.headerContainer}>
        <Text style={styles.headerText}>{header}</Text>
      </View>

      {/* Styled Texts */}
      <Text style={{ color: 'blue', fontSize: 28, fontWeight: 'bold', fontStyle: 'italic', textAlign: 'center' }}>
        Hello World!
      </Text>
      <Text style={{ color: '#DC143C', fontSize: 28, textDecorationLine: 'line-through', textAlign: 'left' }}>
        Rp20.000,00
      </Text>
      <Text style={{ color: 'black', fontSize: 28, textDecorationLine: 'underline', textAlign: 'right' }}>
        Rp10.000,00
      </Text>

      {/* Switch + TextInput + Button */}
      <Switch value={switchV} onValueChange={() => setSwitchV(!switchV)} style={styles.switch} />
      <TextInput
        value={username}
        style={styles.textinput}
        onChangeText={setUsername}
        placeholder="Enter username"
        placeholderTextColor="grey"
      />
      <TouchableOpacity
        style={styles.button}
        onPress={() => setHeader(username || "Home")}
      >
        <Text style={{ color: 'white' }}>Click Here</Text>
      </TouchableOpacity>

      {/* Image with onPress Alert */}
      <TouchableOpacity
        style={styles.imageContainer}
        onPress={() => {
          Alert.alert("Information", "Anda akan menghapus gambar ini?", [
            { text: "Cancel", onPress: () => console.log("cancel ditekan"), style: "cancel" },
            { text: "Ok", onPress: () => console.log("ok ditekan") }
          ]);
        }}
      >
        <Image
          source={{ uri: 'https://reactnative.dev/img/tiny_logo.png' }}
          style={{ width: 100, height: 100 }}
        />
      </TouchableOpacity>

      {/* FlatList Example, each name is clickable */}
      <FlatList
        data={dataMahasiswa}
        style={{ flex: 1, paddingTop: 20 }}
        renderItem={({ item }) => (
          <TouchableOpacity style={styles.flatlistitem} onPress={() => showToast(item.namaMhs + " diklik")}>
            <Text style={{ color: 'white', fontSize: 20, fontWeight: 'bold' }}>{item.namaMhs}</Text>
            <Text style={{ color: 'white' }}>{item.npmMhs}</Text>
          </TouchableOpacity>
        )}
        keyExtractor={item => item.npmMhs}
      />

      {/* Linking to Google */}
      <TouchableOpacity
        style={styles.button}
        onPress={() => Linking.openURL('https://google.com')}
      >
        <Text style={{ color: 'white' }}>Klik Google</Text>
      </TouchableOpacity>

      {/* Fancy Image background with text */}
      <View style={{ marginTop: 32 }}>
        <Image
          source={{ uri: 'https://reactnative.dev/img/tiny_logo.png' }}
          style={{ width: 300, height: 180, position: 'absolute', zIndex: -1, borderRadius: 8 }}
        />
        <Text
          style={{
            color: 'white',
            fontSize: 15,
            backgroundColor: "#0007",
            padding: 8,
            textAlign: 'center',
            borderRadius: 8,
            marginTop: 60,
            marginHorizontal: 8
          }}>
          React-Native
        </Text>
      </View>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#fff' },
  headerContainer: {
    backgroundColor: 'crimson',
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 5,
    paddingVertical: 20,
    marginBottom: 12
  },
  headerText: { color: '#FFF', fontWeight: 'bold', fontSize: 25 },
  button: {
    backgroundColor: '#03fc98',
    paddingVertical: 20,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 20,
    marginHorizontal: 20,
    borderRadius: 20,
    elevation: 3,
  },
  textinput: {
    borderWidth: 1,
    borderColor: '#03fc98',
    marginHorizontal: 20,
    paddingHorizontal: 10,
    borderRadius: 3,
    marginTop: 10,
    color: 'black',
  },
  switch: {
    justifyContent: "center",
    alignItems: "center",
    marginTop: 20,
  },
  imageContainer: {
    justifyContent: "center",
    alignItems: "center",
    marginTop: 20,
  },
  flatlistitem: {
    marginBottom: 20,
    backgroundColor: '#03fc98',
    marginHorizontal: 20,
    borderRadius: 5,
    paddingVertical: 10,
    paddingHorizontal: 20,
  },
});
