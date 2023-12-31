#!/usr/bin/env node

// import { ApolloClient, InMemoryCache, ApolloProvider, gql } from '@apollo/client';
import Apollo from '@apollo/client'
const { ApolloClient, InMemoryCache, gql } = Apollo

const client = new ApolloClient({
  uri: 'https://api.metagame.wtf/v1/graphql',
  cache: new InMemoryCache(),
})

const { data: { player: players } } = (
  await client
  .query({
    query: gql`
      query GetAddresses {
        player { ethereumAddress }
      }
    `,
  })
)

players.map(
  ({ ethereumAddress: addr }) => console.info(addr)
)
