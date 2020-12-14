#!/bin/bash

calcular () {
   resultado=$(($1/3-2))
   if [[ "$resultado" -le 0 ]]
      then 
         echo 0
      else 
         echo $(( $resultado + $( calcular $resultado ) ))
   fi

}

#calcular $1 
#exit 0

rm lastResult ; 
touch lastResult; 
cat input |while read i; 
   do 
   echo $(($(cat lastResult) + $( calcular $i ) ))>result; 
   mv result lastResult ;
done; 
cat lastResult
