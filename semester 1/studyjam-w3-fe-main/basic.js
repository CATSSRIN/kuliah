let variable1 = 0
const variable2 = 2
let array1 = [1, 2, 3, 4, 5]



//if-else

if (1 === 1) {
    console.log('1 = 1')
} else if (1 !== 2) {
    console.log('1 tidak sama dgn 1')
}

//looping

for(variable1; variable1 <= 5; variable1++) {
    console.log(array1[variable1])
}

//array

const array = [1, 2, "GDSC UPN JATIM", 4, 5]
console.log(array[0])



//object

let mahasiswa = {nama: "catss", jurusan: "informatika", test: array1[2], semester: 2}

console.log(mahasiswa['nama'])
console.log(mahasiswa.jurusan)

//function

function catss(param1, param2) {
    console.log(param1)
}

catss("catss1 catss2")

