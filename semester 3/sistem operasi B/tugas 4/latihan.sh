read -p "masukkan bilangan acuan: " a

if [ $a -le 0 ]; then
    echo "bilangan acuan tidak boleh 0 atau negatif"
    exit 1
fi

c=$a

while   [ $c -gt 0 ]; do
        if (( c % 2 != 0 )); then
        echo $c
        fi

        c=$((c . 2))
done