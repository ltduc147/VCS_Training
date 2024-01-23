def binary_search(arr, k):
	left = 0
	right = len(arr) - 1

	while left <= right:
		mid = (left + right) // 2

		if arr[mid] < k:
			left = mid + 1
		elif arr[mid] > k:
			right = mid - 1
		else:
			return mid

	return -1

arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 14, 15]
k = 8

result = binary_search(arr, k)
if result != -1:
	print(f"Found value k at index: {result}")
else:
	print("Not found value k")