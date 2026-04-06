# destructo_sort

I built this for fun, but the results were interesting enough to share.  

# Part 1: d_sort.php
A low-memory, data-dependent sorting algorithm for bounded numeral string ranges with complexity O(n log M).

Where:
- n = number of elements
- M = maximum value (or digit length)

## Idea
This algorithm differs from traditional comparison-based sorting by leveraging the internal structure of values (digits).

Instead of comparing elements directly:
- values are decomposed into digits
- organized into a tree-like structure
- reconstructed in sorted order

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

## Observation
Performance is consistent with:

- Destructo Sort → **O(n log M)**
- Comparison sort → **O(n log n)**

When M is small, log M < log n, giving a clear advantage.

## Notes
This algorithm requires premade - modified BitShift table as the sorting template and consistently references to it.
- Table size is manageable for numeric domains (K=10)
- Performance improves as n grows

# Part 2: d_sort_str_ori.php
The original low-memory, data-dependent sorting algorithm for fixed-length lowercase strings with near-linear complexity:
  O(n · L) ≈ O(n)
Where:
- n = number of elements
- L = string length (fixed)

## Benchmark 1
N = 100,000, M = 4

Destructo Sort: 0.12s  
PHP sort():     0.07s  
QuickSort:      0.26s

## Benchmark 2
N = 1,000,000, M = 4

Destructo Sort: 1.05s  
PHP sort():     1.09s  
QuickSort:      3.64s

## Benchmark 3
N = 10,000,000, M = 4

Destructo Sort: 8.57s  
PHP sort():     15.12s  
QuickSort:      DNF

## Observations
- At small input sizes, PHP's built-in sort (written in C) is faster due to lower constant overhead.
- At medium scale (~1M), performance is comparable.
- At large scale (10M+), Destructo Sort outperforms PHP sort significantly.

This behavior is consistent with:
- Destructo Sort → **O(n · L)** ≈ O(n) for fixed-length strings  
- Comparison sort → **O(n log n · L)**

## Notes
This algorithm requires premade - modified BitShift table as the sorting template and consistently references to it.
- Current implementation with K=20 supports characters 'a' to 't'
- Full lowercase (`a–z`) is theoretically possible but requires extremely powerful computer
- Full mixed-case alphabet or alphanumeric requires **ungodly amount of memory**

## How it works
This algorithm uses a **precomputed BitShift routing table** to eliminate comparisons.

Instead of comparing letter or number:
- input is encoded into a compact structural representation  
- routing table determines ordering  
- output is reconstructed in sorted order

# Part 3: d_sort_str.php
An even lower-memory, data-dependent sorting algorithm for fixed-length lowercase strings with near-linear complexity:
  O(n · L · K) ≈ O(n)
Where:
- n = number of elements
- L = string length (fixed)
- K = domain size

This version trades performance loss for massive gains in scalability and flexibility.
Performance is sensitive to K (alphabet size), which acts as a constant multiplier.

## Benchmark 1
N = 100,000, M = 4

Destructo (Original):   0.11s  
Destructo Sort (K=20):  0.14s  
Destructo Sort (K=26):  0.14s  

## Benchmark 2
N = 1,000,000, M = 4, K=20

Destructo (Original):   0.86s  
Destructo Sort (K=20):  0.88s  
Destructo Sort (K=26):  1.22s  

## Benchmark 3
N = 10,000,000, M = 4, K=20

Destructo (Original):   8.27s  
Destructo Sort (K=20):  8.58s  
Destructo Sort (K=26):  9.63s  

## Observations
- At small input sizes, the original version is faster due to lower constant overhead
- At medium scale (~1M), performance is still comparable.
- At large scale (10M+), original Destructo Sort is slightly faster
- Original destructo sort requires 0.8s of BitShift table preparation
- Increasing K to 26 (full lowercase alphabet), introduces a linear performance cost with respect to K

## Notes
This version does not require a precomputed BitShift table, reducing memory usage significantly
- Current implementation with K=27 supports characters 'a' to 'z' and space ' '
- Full upper and lower case alpha-numeric (K=63) is possible at additional performance cost

## How it works
This algorithm uses a **binary-encoded routing via bit operations** to eliminate comparisons.

Instead of comparing letter or number:
- input is encoded as an integer representation
- bit patterns are used to determine relative ordering
- output is reconstructed in sorted order

## When to use
This algorithm performs best when:
- The dataset size (n) is large
- Small or bounded value domain
- Fixed-length data
- Situations where comparisons are expensive

## When NOT to use
- When general-purpose sorting is sufficient
- Large alphabets / high-entropy domains
  
## Notes
To minimize memory usage, avoid loading all data into an array before sorting.

Instead:
- insert values directly into the data structure
- then call `output_recursive` to retrieve sorted output  

(Some modifications to the source code may be required.)

## Key idea
Instead of comparing elements, this algorithm groups values by their digits and progressively organizes them using a tree-like structure. This reduces dependence on log n and replaces it with log M.

## Final thoughts
This is not a general-purpose sorting algorithm, this work explores the trade-off between precomputation (time vs memory) and on-the-fly computation (time vs flexibility).

But in the right conditions, it can significantly outperform traditional approaches.

Contributions, feedback, and use cases are welcome.
