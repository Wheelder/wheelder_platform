# function to find the prefix sum array
def prefSum(arr):
    n = len(arr)
    
    # to store the prefix sum
    prefixSum = [0] * (n + 1)

    # initialize the first element
    prefixSum[0] = arr[0]

    # Adding present element with previous element
    for i in range(n):
        prefixSum[i+1] = prefixSum[i] + arr[i]
    
    return prefixSum

    def prefSum_rang(i,j):
        return prefixSum[j + 1] + prefixSum[i]





if __name__ == "__main__":
    arr = [10, 20, 10, 5, 15]
    prefixSum = prefSum(arr)
    prefSum_range = prefSum_rang(2,3)

    for i in prefixSum:
        print(i, end=" ")
    
    for re in prefSum_range:
        print(re, end=" ")