echo "pilih tipe bilangan"
select bilangan in ganjil genap keluar
do
    case $bilangan in
    genap)

    read -p "masukkan bilangan acuan: " a

    if [ $a -le 0 ]; then
        echo "bilangan acuan tidak boleh 0 atau negatif"
        exit 1
    fi

    c=0

    while [ $a -gt $c ]; do
    if (( c != $a)); then
        echo $c
    fi
    c=$((c + 2))
    done
    ;;

    ganjil)
    read -p "masukkan bilangan acuan: " a
    if [ $a - le 0 ]; then
        echo "bilangan acuan tidak boleh 0 atau negatif"
        exit 1
    fi

    c=1
    while [ $c -lt $a ]; do
    if [ c != $a ]; then
        echo $c
    fi
    c=$((c + 2))
    done
    ;;

    keluar)
    echo "terima kasih"
    exit 0
    ;;
    
    *)
    echo "tidak ada di menu"
    ;;
    esac
    done