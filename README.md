# destructo_sort
sorting algorithm for numerical string with complexity of O(n log M) 

N is the number of data while M is the 'value' of the numerical string.

I have no idea if someone else wrote the concept of this algorithm before, but at least this is my original implementation of destructive sorting algorithm.

Most sorting algorithm works on the orirginal data array, but this algorithm, ditched the original array and used only the data.  Hence, destructive if using array_pop, and data is stored in a tree structure, sorted.  Then afterward will output the data in a sorted manner.

For I'm comparing this sorting algorithm to QuickSort from https://zetcode.com/php/quick-sort/, RadixSort from https://zetcode.com/php/radix-sort/ and PHP's default sort function.  

The result is pretty promising and consistent with O(n log M)

This algorithm requires premade - modified BitShift table to help the sorting process, and it worked pretty well.

And yes, I cheated a bit by making it numerical string instead of just storing numbers as Integer.

To be honest I don't know what kind of usage of this, just feel free to use if you needed.
Just to add a note, to save memory, please don't load data from file to an array before sorting.  Instead, just store the value in it's datastructure and then call output_recursive. Some modification required.
