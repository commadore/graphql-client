# PHP GraphQL Client

Essentially a wrapper for Guzzle for GraphQL queries.

Inject a Guzzle Client, one method that takes a GraphQL Query, (optional) variables, (optional) headers.
 
Returns GraphQLResponse object with getPayload, getErrors methods to get at your delicious data. Both return associative arrays.

Allows Guzzle exceptions to bubble up other than TransferException which is thrown as a custom Network Exception

