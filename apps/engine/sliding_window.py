
def fixed_window(arr, k):

    #initial window sum
    window_sum = sum(arr[:k])
    max_sum = window_sum 

    #Move the window across the array
    for i in range(k , len(arr)):
        #Add the incoming and remove the outgoing one
        window_sum += arr[i] - arr[i - k]

        #Track the max window seen so far
        max_sum = max(max_sum, window_sum) 

    return max_sum

#Test cases
'''arr = [ 1,2,3,5]
k = 3

print(fixed_window(arr,k))
'''
#Test case2:
print("Test case 2:")
arr1 = [5, -1, 3, 2, 7]
k1 = 2

print(fixed_window(arr1,k1))

arr2 = [10]
k2 = 10

print(fixed_window(arr2,k2))

#Edge cases:

arr3 =[]
k = 0

print(fixed_window(arr3,k))

#Case A

arr4 = [2, 4, 6]
k4 = len(arr4)

Expected: 12

print(fixed_window(arr4,k4))

#Case B
#arr = [1,2]
#k > len(arr)
#k =3
#print(fixed_window(arr,k))
#Expected: undefined
#Case C
'''
arr = []
k = 0 or k > 0
print(fixed_window(arr,k))

arr = [1000000, 2000000, 3000000]
k = 2

print(fixed_window(arr,k))
'''
arr = [1,2,3]
k = 0

print(fixed_window(arr,k))