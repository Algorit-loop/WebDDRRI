#include <bits/stdc++.h>
using namespace std;

int __ac(string Noti, string Feedback){
    cout << Noti;
    cerr << Feedback;
    return 0;
}
int __wa(string Noti, string Feedback){
    cout << Noti;
    cerr << Feedback;
    return 1;
}
int __partial(double point, string Noti, string Feedback){
    // cerr << "points ";
    cerr << point << "\n";
    cout << Noti;
    cerr << Feedback;
    return 2;
}
bool isPalin(string s){
    for (int i = 0; i < s.size(); i++){
        if (s[i] != s[s.size() - i - 1]) return false;
    }
    return true;
}
int main(int argc, char** argv) {
    ifstream inp(argv[1]);
    ifstream out(argv[2]);
    ifstream ans(argv[3]);
    bool ck = 0;
    try{
        // ------ input ------
        int n , k;
        inp >> n >> k;
        vector <char> s(n + 1);
        vector <int> d(2*n + 1,0);
        for (int i = 1; i <= n; i++) inp >> s[i];
        // ------ answer ------
        string ansYN; ans >> ansYN;
        // ------ output ------
        string outYN; out >> outYN;
        if (ansYN == "NO"){
            if (outYN == "NO"){
                string cl;
                while (out >> cl){
                  return __wa("in thua` cai' gi` roi` ?", "");
                }
                return __ac("Bingooooo", "");
            }
            else return __wa("lam gi ma sai YES/NO roi", "");
        }
        // ------ Process ------
        ck = 0;
        if (ansYN != outYN){
            return __wa("lam gi ma sai YES/NO roi","");
        }
        ck = 1;
        string out_n;
        out >> out_n;
        int n_out = stoi(out_n);
        // cout << "n_out = " << n_out << "\n";
        stringstream tmp;
        for (int is = 1; is <= n_out; is++){
            tmp.clear();
            string ds_out; 
            if (out.peek() == '\n') out.ignore();
            getline(out, ds_out);
            tmp.str(ds_out);
            string str_x;
            string g = "";
            while (tmp >> str_x){
                int x = stoi(str_x);
                d[x]++;
                g = g + s[x];
                // cout << x << " ";
            }
            // cout << "\n";
            if (!isPalin(g) || (int)g.size() < k || (int)g.size() % 2 == 0){
                return __partial(0.421,"sai truy van roi ?", "");
            }
        }
        string cl;
        while (out >> cl){
            return __partial(0.421,"in thua` cai' gi` roi` ?", "");
        }
        for (int i = 1; i <= n; i++)
            if (d[i] != 1) return __partial(0.421,"co' van' de` ve` chi? so' roi`", "");
        return __ac("Bingooooo", "ha ?");
    }
    catch (exception e){
        // cout << "Looks like the format is wrong";
        // cerr << e.what();
        // return __ac("vl rac","wtf?");
        if (ck){
            return __partial(0.421,"Looks like the format is wrong","");
        } else return __wa("Looks like the format is wrong:)","");
    }
}