rm lastResult ; touch lastResult; cat input |while read i; do echo $(($(cat lastResult) + $(($i/3-2))))>result; mv result lastResult ;done; cat lastResult
