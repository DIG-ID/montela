#!/usr/bin/env python3
"""
Figma Desktop MCP client (local server at 127.0.0.1:3845, Dev Mode MCP on).
No packages needed. Large responses are split on "\n" only (str.splitlines
breaks on U+2028 inside copy).

Usage:
  python3 .claude/tools/fig.py list
  python3 .claude/tools/fig.py get_design_context '{"nodeId":"5895:20070"}' /path/out
  python3 .claude/tools/fig.py get_screenshot '{"nodeId":"5895:20070"}' /path/out
Images are written as <out>-N.png; text goes to stdout.
"""
import json, sys, urllib.request, base64, os
URL = "http://127.0.0.1:3845/mcp"
def post(body, sid=None):
    h = {"Content-Type": "application/json", "Accept": "application/json, text/event-stream"}
    if sid: h["mcp-session-id"] = sid
    r = urllib.request.urlopen(urllib.request.Request(URL, json.dumps(body).encode(), h), timeout=600)
    sid = r.headers.get("mcp-session-id") or sid
    raw = r.read().decode()
    msgs, buf = [], []
    for l in raw.split("\n") + [""]:
        if l.startswith("data:"):
            buf.append(l[5:].lstrip())
        elif not l.strip() and buf:
            msgs.append(json.loads("\n".join(buf))); buf = []
    if not msgs and raw.strip(): msgs = [json.loads(raw)]
    return sid, msgs
sid, _ = post({"jsonrpc":"2.0","id":1,"method":"initialize","params":{"protocolVersion":"2025-03-26","capabilities":{},"clientInfo":{"name":"cc","version":"1"}}})
post({"jsonrpc":"2.0","method":"notifications/initialized"}, sid)
method = sys.argv[1]
if method == "list":
    _, m = post({"jsonrpc":"2.0","id":2,"method":"tools/list"}, sid)
    for t in m[-1]["result"]["tools"]:
        print(t["name"], json.dumps(t["inputSchema"].get("properties", {})))
else:
    args = json.loads(sys.argv[2]); out = sys.argv[3]
    _, m = post({"jsonrpc":"2.0","id":2,"method":"tools/call","params":{"name":method,"arguments":args}}, sid)
    res = m[-1].get("result") or m[-1]
    n = 0
    for c in res.get("content", []):
        if c["type"] == "text": print(c["text"])
        elif c["type"] == "image":
            n += 1; p = f"{out}-{n}.png"; open(p, "wb").write(base64.b64decode(c["data"])); print("IMAGE:", p)
    if "error" in m[-1]: print(m[-1]["error"])
