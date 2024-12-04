#include <iostream>
#include <algorithm>
#include <cstring>
#include <cmath>
#include <iomanip>
#include <stdio.h>
#include <chrono>
#include <random>

#include <set>
#include <unordered_set>
#include <bitset>
#include <map>
#include <unordered_map>
#include <queue>
#include <deque>
#include <vector>
#include <ext/pb_ds/assoc_container.hpp>
#include <ext/pb_ds/tree_policy.hpp>

using namespace std;
using namespace __gnu_pbds;

#define ll long long
#define ld long double
#define fi first
#define se second
#define pb push_back
#define mp make_pair
#define ii pair<int,int>
#define pli pair<ll,int>
#define pll pair<ll,ll>
#define pil pair<int,ll>
#define plii pair<ll,pair<int,int>>
#define heapmax(type) priority_queue<type>
#define heapmin(type) priority_queue<type,vector<type>,greater<type>>
#define ordered_multiset  tree<int, null_type, less_equal<int>, rb_tree_tag, tree_order_statistics_node_update>
#define ordered_set       tree<int, null_type, less<int>, rb_tree_tag, tree_order_statistics_node_update>
#define FASTIO ios::sync_with_stdio(NULL); cin.tie(NULL); cout.tie(NULL);
#define sz(x) (int)x.size()
#define all(x) (x).begin(),(x).end()
#define getbit(mask,i) ((mask >> i) & 1)

template<typename T> bool maximize(const T &res, const T &val) { if (res <  val) { res = val; return true; } return false; }
template<typename T> bool minimize(const T &res, const T &val) { if (res >  val) { res = val; return true; } return false; }
template<typename T> ll rv_num(T x){
    ll res = 0;
    while (x > 0) res = res*10 + x % 10 , x/=10;
    return res;
}
const ll mod = 1e9 + 7;
const ld PI = acos(-1);
const int N = 2e5 + 100;
const int N_Trie = 1e5;
const int N_ST = 2e5 + 10;
const int N_BIT = 2e5 + 10;
const int ooint = (1LL << 31) - 1;
// const ll ooll = (1LL << 63) - 1;
const int LIM = 1e7; // N_Prime
const int N_MST = 1e5; // N of Merge Sort Tree
const int N_Hash = 2e6 + 10;

        mt19937 rng(chrono::steady_clock::now().time_since_epoch().count());

void file(const string FILE){
    if (fopen((FILE + ".INP").c_str(),"r")){
        freopen((FILE + ".INP").c_str(), "r", stdin);
        freopen((FILE + ".OUT").c_str(), "w", stdout);
    }
}
ll range(ll l, ll r){
    return l + (1ULL * rng() * 1ULL * rng() + rng() + rng() + 1) % (1ULL *(r - l + 1));
}

ll power(ll a,ll b,ll c){ // O(log(b))
    ll res = 1;
    a = a % c;
    for (; b > 0; b >>= 1 , a = a * a % c)
        if (b & 1) res = res * a % c;
    return res;
}
string s; int n;
inline string cv_string(ll x){
    if (x == 0) return "0";
    string res = "";
    while (x > 0){
        res = res + char(x%10+48);
        x /= 10;
    }
    reverse(all(res));
    return res;
}
vector <int> findrp(const string &s, const int &i, const int &j){
    int len = j - i + 1;
    vector <int> v;
    for (int k = 1; k <= len / 2; k++){
        if (len % k == 0){
            bool check = true;
            for (int p = i; p <= j; p++){
                if (s[p] != s[i + (p - i) % k]){
                    check = false;
                    break;
                }
            }
            if (check) v.pb(k);
        }
    }
    return v;
}
int main()
{
    FASTIO;
    file("compress");
    cin >> n;
    cin >> s;
    // n = s.size();
    //for (int i = 1; i <= 300; i++) s = s + char(range(97,97+1));
    clock_t st,en; st = clock();
    vector <vector<string>> dp(n,vector<string>(n));
    for (int i = 0; i < n; i++) dp[i][i] = s[i];
    for (int len = 2; len <= n; len++){
        for (int i = 0; i < n - len + 1; i++){
            int j = i + len - 1;
            dp[i][j] = s.substr(i,len);
            for (int k = i; k < j; k++){
                if (dp[i][j].size() > dp[i][k].size()+dp[k+1][j].size()){
                    dp[i][j] = dp[i][k]+dp[k+1][j];
                }
                else
                if (dp[i][j].size() == dp[i][k].size() + dp[k+1][j].size()){
                    dp[i][j] = min(dp[i][j],dp[i][k]+dp[k+1][j]);
                }
            }
            // done dp[i][j] voi phep ghep
            vector <int> rpv = findrp(s,i,j);
            for (auto g : rpv){
                int m = len / g;
                string rp = cv_string(m) + "(" + dp[i][i + g - 1] + ")";
                if (rp.size() < dp[i][j].size()){
                    dp[i][j] = rp;
                }
                else
                if (rp.size() == dp[i][j].size()){
                    dp[i][j] = min(dp[i][j], rp);
                }
            }
        }
    }
    cout << dp[0][n-1] << "\n";
    en = clock();
    // cout << "Time: " << (double)(en-st)/CLOCKS_PER_SEC;
    return 0;
}
