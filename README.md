# destructo_sort

I built this for fun, but the results were interesting enough to share.  

A low-memory, data-dependent sorting algorithm for bounded numeral string ranges with complexity O(n log M).

Where:
- n = number of elements
- M = maximum value (or digit length)

This algorithm differs from traditional comparison-based sorting by leveraging the internal structure of numeric values (digits). Instead of comparing elements directly, it organizes data into a tree-like structure and outputs them in sorted order.

It is particularly efficient when:
- n is large
- M is relatively small

This is my original implementation of a destructive sorting algorithm, although similar ideas may already exist.

I compared this algorithm against:
- QuickSort from https://zetcode.com/php/quick-sort/  
- RadixSort from https://zetcode.com/php/radix-sort/  
- PHP's built-in `sort()` function

## Benchmark 1
N = 1,000,000, M = 10,000

Destructo Sort: 0.80s  
PHP sort():     1.79s  
Radix Sort:     3.59s  
QuickSort:      6.99s

## Benchmark 2
N = 100,000, M = 10,000

Destructo Sort: 0.07s  
PHP sort():     0.11s  
Radix Sort:     0.29s  
QuickSort:      0.32s

## Benchmark 3
N = 1,000,000, M = 1,000

Destructo Sort: 0.52s  
PHP sort():     1.43s  
Radix Sort:     DNF  
QuickSort:      27.85s

## Benchmark 4
N = 1,000,000, M = 100

Destructo Sort: 0.45s  
PHP sort():     1.32s  
Radix Sort:     DNF  
QuickSort:      DNF

The result is promising and consistent with O(n log M)

This algorithm requires premade - modified BitShift table as the sorting template and consistently references to it..

## When to use

This algorithm performs best when:
- The dataset size (n) is large
- The value range (M) is relatively small or bounded
- Memory usage is a concern
- Data can be processed in a streaming or incremental manner

## When NOT to use

- When values have a very large range (large M)
- When a stable sort is required
- When general-purpose sorting is sufficient

Contributions, feedback, and use cases are welcome.

## Notes

To minimize memory usage, avoid loading all data into an array before sorting.

Instead:
- insert values directly into the data structure  
- then call `output_recursive` to retrieve sorted output  

(Some modifications to the source code may be required.)


## Key idea

Instead of comparing elements, this algorithm groups values by their digits and progressively organizes them using a tree-like structure. This reduces dependence on log n and replaces it with log M.

To be honest this is not a general-purpose sorting algorithm, but it can significantly outperform traditional approaches in the right conditions. 

Contributions, feedback, and use cases are welcome.
