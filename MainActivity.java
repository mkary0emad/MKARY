

/* FIREBASE APP CONTROL */
final String FIREBASE_CONTROL_URL =
    "https://newproject7-b87ad-default-rtdb.firebaseio.com/app_control.json";

new Thread(new Runnable() {
    @Override
    public void run() {
        java.net.HttpURLConnection conn = null;
        try {
            java.net.URL url = new java.net.URL(FIREBASE_CONTROL_URL);
            conn = (java.net.HttpURLConnection) url.openConnection();
            conn.setRequestMethod("GET");
            conn.setConnectTimeout(8000);
            conn.setReadTimeout(8000);
            conn.setUseCaches(false);

            int code = conn.getResponseCode();
            if (code != 200) return;

            java.io.InputStream in = conn.getInputStream();
            java.io.BufferedReader reader = new java.io.BufferedReader(
                new java.io.InputStreamReader(in, "UTF-8")
            );
            StringBuilder response = new StringBuilder();
            String line;
            while ((line = reader.readLine()) != null) {
                response.append(line);
            }
            reader.close();
            in.close();

            org.json.JSONObject control =
                new org.json.JSONObject(response.toString());

            final boolean locked =
                control.optBoolean("locked", false);
            final boolean maintenance =
                control.optBoolean("maintenance", false);
            final boolean forceUpdate =
                control.optBoolean("force_update", false);
            final int minVersion =
                control.optInt("min_version", 1);
            final int latestVersion =
                control.optInt("latest_version", 1);
            final String message =
                control.optString("message", "");
            final String updateUrl =
                control.optString("update_url", "");

            final int currentVersion = 1;

            final boolean mustUpdate =
                forceUpdate || currentVersion < minVersion;

            if (locked || maintenance || mustUpdate) {
                MainActivity.this.runOnUiThread(new Runnable() {
                    @Override
                    public void run() {
                        String title;
                        String text;

                        if (locked) {
                            title = "التطبيق مقفول";
                            text = message.length() > 0
                                ? message
                                : "التطبيق غير متاح حاليًا.";
                        } else if (maintenance) {
                            title = "صيانة";
                            text = message.length() > 0
                                ? message
                                : "التطبيق تحت الصيانة حاليًا.";
                        } else {
                            title = "تحديث مطلوب";
                            text = message.length() > 0
                                ? message
                                : "يتوفر إصدار جديد من التطبيق.";
                        }

                        final android.app.AlertDialog dialog =
                            new android.app.AlertDialog.Builder(
                                MainActivity.this
                            )
                            .setTitle(title)
                            .setMessage(text)
                            .setCancelable(false)
                            .setPositiveButton(
                                mustUpdate && updateUrl.length() > 0
                                ? "تحديث"
                                : "إغلاق",
                                null
                            )
                            .create();

                        dialog.setOnShowListener(
                            new android.content.DialogInterface.OnShowListener() {
                                @Override
                                public void onShow(
                                    android.content.DialogInterface d
                                ) {
                                    android.widget.Button button =
                                        dialog.getButton(
                                            android.app.AlertDialog.BUTTON_POSITIVE
                                        );

                                    if (mustUpdate && updateUrl.length() > 0) {
                                        button.setOnClickListener(
                                            new android.view.View.OnClickListener() {
                                                @Override
                                                public void onClick(
                                                    android.view.View v
                                                ) {
                                                    try {
                                                        android.content.Intent intent =
                                                            new android.content.Intent(
                                                                android.content.Intent.ACTION_VIEW,
                                                                android.net.Uri.parse(updateUrl)
                                                            );
                                                        MainActivity.this.startActivity(intent);
                                                    } catch (Exception ignored) {
                                                    }
                                                }
                                            }
                                        );
                                    } else {
                                        button.setOnClickListener(
                                            new android.view.View.OnClickListener() {
                                                @Override
                                                public void onClick(
                                                    android.view.View v
                                                ) {
                                                    MainActivity.this.finish();
                                                }
                                            }
                                        );
                                    }
                                }
                            }
                        );

                        dialog.setOnDismissListener(
                            new android.content.DialogInterface.OnDismissListener() {
                                @Override
                                public void onDismiss(
                                    android.content.DialogInterface d
                                ) {
                                    if (locked || maintenance || mustUpdate) {
                                        if (!MainActivity.this.isFinishing()) {
                                            MainActivity.this.finish();
                                        }
                                    }
                                }
                            }
                        );

                        dialog.show();
                    }
                });
            }

        } catch (Exception ignored) {
            // إذا تعذر الاتصال، التطبيق يكمل العمل طبيعيًا.
        } finally {
            if (conn != null) {
                try { conn.disconnect(); } catch (Exception ignored) {}
            }
        }
    }
}).start();

final int BG =
android.graphics.Color.rgb(10, 12, 15);

final int BAR =
android.graphics.Color.rgb(20, 23, 28);

final int CARD =
android.graphics.Color.rgb(27, 31, 37);

final int CARD2 =
android.graphics.Color.rgb(35, 40, 47);

final int WHITE =
android.graphics.Color.rgb(245, 247, 250);

final int GRAY =
android.graphics.Color.rgb(155, 163, 174);

final int BLUE =
android.graphics.Color.rgb(82, 105, 255);

final int BLUE2 =
android.graphics.Color.rgb(65, 88, 240);

final int DP =
(int) (
getResources()
.getDisplayMetrics()
.density + 0.5f
);

getWindow().setStatusBarColor(BG);
getWindow().setNavigationBarColor(BG);

final android.widget.FrameLayout ROOT =
new android.widget.FrameLayout(
MainActivity.this
);

ROOT.setBackgroundColor(BG);

setContentView(ROOT);

final android.widget.LinearLayout MAIN =
new android.widget.LinearLayout(
MainActivity.this
);

MAIN.setOrientation(
android.widget.LinearLayout.VERTICAL
);

MAIN.setBackgroundColor(BG);

ROOT.addView(
MAIN,
new android.widget.FrameLayout.LayoutParams(
-1,
-1
)
);

final android.widget.LinearLayout TOP =
new android.widget.LinearLayout(
MainActivity.this
);

TOP.setOrientation(
android.widget.LinearLayout.HORIZONTAL
);

TOP.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

TOP.setPadding(
4,
3,
4,
3
);

TOP.setBackgroundColor(BAR);

MAIN.addView(
TOP,
new android.widget.LinearLayout.LayoutParams(
-1,
90
)
);

final android.widget.TextView MENU_BUTTON =
new android.widget.TextView(
MainActivity.this
);

MENU_BUTTON.setText("☰");
MENU_BUTTON.setTextColor(WHITE);
MENU_BUTTON.setTextSize(30);
MENU_BUTTON.setGravity(
android.view.Gravity.CENTER
);

TOP.addView(
MENU_BUTTON,
new android.widget.LinearLayout.LayoutParams(
62,
80
)
);

final android.widget.LinearLayout PATH_AREA =
new android.widget.LinearLayout(
MainActivity.this
);

PATH_AREA.setOrientation(
android.widget.LinearLayout.VERTICAL
);

PATH_AREA.setGravity(
android.view.Gravity.CENTER
);

TOP.addView(
PATH_AREA,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

final android.widget.TextView TOP_PATH =
new android.widget.TextView(
MainActivity.this
);

TOP_PATH.setTextColor(WHITE);
TOP_PATH.setTextSize(18);
TOP_PATH.setTypeface(
android.graphics.Typeface.DEFAULT,
android.graphics.Typeface.BOLD
);

TOP_PATH.setGravity(
android.view.Gravity.CENTER
);

TOP_PATH.setSingleLine(true);

TOP_PATH.setEllipsize(
android.text.TextUtils.TruncateAt.MIDDLE
);

PATH_AREA.addView(
TOP_PATH,
new android.widget.LinearLayout.LayoutParams(
-1,
43
)
);

final android.widget.TextView TOP_COUNT =
new android.widget.TextView(
MainActivity.this
);

TOP_COUNT.setTextColor(GRAY);
TOP_COUNT.setTextSize(11);

TOP_COUNT.setGravity(
android.view.Gravity.CENTER
);

PATH_AREA.addView(
TOP_COUNT,
new android.widget.LinearLayout.LayoutParams(
-1,
25
)
);

final android.widget.TextView HIDDEN_DOT =
new android.widget.TextView(
MainActivity.this
);

HIDDEN_DOT.setText("●");
HIDDEN_DOT.setTextColor(GRAY);
HIDDEN_DOT.setTextSize(18);
HIDDEN_DOT.setGravity(
android.view.Gravity.CENTER
);

TOP.addView(
HIDDEN_DOT,
new android.widget.LinearLayout.LayoutParams(
45,
-1
)
);

final android.widget.TextView MORE =
new android.widget.TextView(
MainActivity.this
);

MORE.setText("⋮");
MORE.setTextColor(WHITE);
MORE.setTextSize(31);
MORE.setGravity(
android.view.Gravity.CENTER
);

TOP.addView(
MORE,
new android.widget.LinearLayout.LayoutParams(
55,
-1
)
);

final android.widget.LinearLayout PANES =
new android.widget.LinearLayout(
MainActivity.this
);

PANES.setOrientation(
android.widget.LinearLayout.HORIZONTAL
);

PANES.setWeightSum(2);

MAIN.addView(
PANES,
new android.widget.LinearLayout.LayoutParams(
-1,
0,
1
)
);

final java.io.File STORAGE =
android.os.Environment
.getExternalStorageDirectory();

final android.widget.LinearLayout BOTTOM =
new android.widget.LinearLayout(
MainActivity.this
);

BOTTOM.setOrientation(
android.widget.LinearLayout.HORIZONTAL
);

BOTTOM.setGravity(
android.view.Gravity.CENTER
);

BOTTOM.setPadding(
8 * DP,
4 * DP,
8 * DP,
4 * DP
);

BOTTOM.setBackgroundColor(BAR);

MAIN.addView(
BOTTOM,
new android.widget.LinearLayout.LayoutParams(
-1,
54 * DP
)
);

final android.widget.TextView BACK =
new android.widget.TextView(
MainActivity.this
);

BACK.setText("‹");
BACK.setTextColor(WHITE);
BACK.setTextSize(32);
BACK.setGravity(
android.view.Gravity.CENTER
);

BOTTOM.addView(
BACK,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

final android.widget.TextView FORWARD =
new android.widget.TextView(
MainActivity.this
);

FORWARD.setText("›");
FORWARD.setTextColor(WHITE);
FORWARD.setTextSize(32);
FORWARD.setGravity(
android.view.Gravity.CENTER
);

BOTTOM.addView(
FORWARD,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

final android.widget.FrameLayout CREATE_SLOT =
new android.widget.FrameLayout(
MainActivity.this
);

BOTTOM.addView(
CREATE_SLOT,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

final android.widget.TextView CREATE =
new android.widget.TextView(
MainActivity.this
);

CREATE.setText("+");
CREATE.setTextColor(WHITE);
CREATE.setTextSize(27);
CREATE.setGravity(
android.view.Gravity.CENTER
);

final android.graphics.drawable.GradientDrawable CREATE_BG =
new android.graphics.drawable.GradientDrawable();

CREATE_BG.setShape(
android.graphics.drawable.GradientDrawable.OVAL
);

CREATE_BG.setColor(BLUE);

CREATE.setBackground(CREATE_BG);
CREATE.setElevation(8);

android.widget.FrameLayout.LayoutParams CREATE_LP =
new android.widget.FrameLayout.LayoutParams(
50,
50,
android.view.Gravity.CENTER
);

CREATE_SLOT.addView(
CREATE,
CREATE_LP
);

final android.widget.TextView SYNC =
new android.widget.TextView(
MainActivity.this
);

SYNC.setText("⇄");
SYNC.setTextColor(WHITE);
SYNC.setTextSize(27);
SYNC.setGravity(
android.view.Gravity.CENTER
);

BOTTOM.addView(
SYNC,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

final android.widget.TextView UP =
new android.widget.TextView(
MainActivity.this
);

UP.setText("↑");
UP.setTextColor(WHITE);
UP.setTextSize(30);
UP.setGravity(
android.view.Gravity.CENTER
);

BOTTOM.addView(
UP,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

final android.widget.LinearLayout[] BOX =
new android.widget.LinearLayout[2];

final android.widget.LinearLayout[] LIST =
new android.widget.LinearLayout[2];

final java.io.File[] DIR =
new java.io.File[2];

final java.util.ArrayList<String>[] HISTORY =
new java.util.ArrayList[2];

final java.util.ArrayList<String>[] FUTURE =
new java.util.ArrayList[2];

final boolean[] ACTIVE =
new boolean[2];

final boolean[] SEARCHING =
new boolean[2];

final java.util.ArrayList<java.io.File>[] SEARCH_RESULTS =
new java.util.ArrayList[2];

final boolean[] SHOW_HIDDEN =
new boolean[2];

final boolean[] REMOTE_MODE =
new boolean[2];

final String[] REMOTE_PATH =
new String[2];

final int[] REMOTE_INDEX =
new int[2];

final String[] REMOTE_LOCAL_PATH =
new String[2];

final java.util.HashMap<String, String>[] REMOTE_SUB =
new java.util.HashMap[2];

HISTORY[0] =
new java.util.ArrayList<String>();

HISTORY[1] =
new java.util.ArrayList<String>();

FUTURE[0] =
new java.util.ArrayList<String>();

FUTURE[1] =
new java.util.ArrayList<String>();

SEARCH_RESULTS[0] =
new java.util.ArrayList<java.io.File>();

SEARCH_RESULTS[1] =
new java.util.ArrayList<java.io.File>();

DIR[0] = STORAGE;
DIR[1] = STORAGE;

ACTIVE[0] = true;
ACTIVE[1] = false;

SHOW_HIDDEN[0] = false;
SHOW_HIDDEN[1] = false;

REMOTE_MODE[0] = false;
REMOTE_MODE[1] = false;
REMOTE_PATH[0] = "";
REMOTE_PATH[1] = "";
REMOTE_INDEX[0] = -1;
REMOTE_INDEX[1] = -1;

REMOTE_LOCAL_PATH[0] = STORAGE.getAbsolutePath();
REMOTE_LOCAL_PATH[1] = STORAGE.getAbsolutePath();

REMOTE_SUB[0] = new java.util.HashMap<String, String>();
REMOTE_SUB[1] = new java.util.HashMap<String, String>();

final android.widget.ScrollView[] SCROLL =
new android.widget.ScrollView[2];

for (
int side = 0;
side < 2;
side++
) {

final int SIDE = side;

BOX[SIDE] =
new android.widget.LinearLayout(
MainActivity.this
);

BOX[SIDE].setOrientation(
android.widget.LinearLayout.VERTICAL
);

BOX[SIDE].setBackgroundColor(
SIDE == 0
? android.graphics.Color.rgb(15, 18, 23)
: BG
);

android.widget.LinearLayout.LayoutParams SIDE_PARAM =
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
);

SIDE_PARAM.setMargins(
1,
0,
1,
0
);

PANES.addView(
BOX[SIDE],
SIDE_PARAM
);

SCROLL[SIDE] =
new android.widget.ScrollView(
MainActivity.this
);

SCROLL[SIDE].setFillViewport(true);
SCROLL[SIDE].setClipToPadding(false);

BOX[SIDE].addView(
SCROLL[SIDE],
new android.widget.LinearLayout.LayoutParams(
-1,
0,
1
)
);

LIST[SIDE] =
new android.widget.LinearLayout(
MainActivity.this
);

LIST[SIDE].setOrientation(
android.widget.LinearLayout.VERTICAL
);

LIST[SIDE].setPadding(
5,
6,
5,
15
);

SCROLL[SIDE].addView(
LIST[SIDE]
);

BOX[SIDE].setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

ACTIVE[0] =      
    SIDE == 0;      

    ACTIVE[1] =      
    SIDE == 1;      

    BOX[0].setBackgroundColor(      
        SIDE == 0      
        ? android.graphics.Color.rgb(15, 18, 23)      
        : BG      
    );      

    BOX[1].setBackgroundColor(      
        SIDE == 1      
        ? android.graphics.Color.rgb(15, 18, 23)      
        : BG      
    );      
}

}

);

}



class FM {

java.util.ArrayList<String> savedHosts = new java.util.ArrayList<String>();
java.util.ArrayList<String> savedUsers = new java.util.ArrayList<String>();
java.util.ArrayList<String> savedPasswords = new java.util.ArrayList<String>();
java.util.ArrayList<Integer> savedPorts = new java.util.ArrayList<Integer>();
java.util.ArrayList<Integer> savedTypes = new java.util.ArrayList<Integer>();
java.util.ArrayList<String> savedRemarks = new java.util.ArrayList<String>();

void saveConnections() {

try {

android.content.SharedPreferences sp =
MainActivity.this.getSharedPreferences(
"network_storage",
android.content.Context.MODE_PRIVATE
);

org.json.JSONArray array =
new org.json.JSONArray();

for (int i = 0; i < savedHosts.size(); i++) {

org.json.JSONObject o =
new org.json.JSONObject();

o.put("host", savedHosts.get(i));
o.put("user", savedUsers.get(i));
o.put("pass", savedPasswords.get(i));
o.put("port", savedPorts.get(i));
o.put("type", savedTypes.get(i));

String remark =
savedRemarks.size() > i
? savedRemarks.get(i)
: "";

o.put("remark", remark);

array.put(o);
}

sp.edit()
.putString("connections", array.toString())
.apply();

} catch (Exception ignored) {
}
}

void loadSavedConnections() {

savedHosts.clear();
savedUsers.clear();
savedPasswords.clear();
savedPorts.clear();
savedTypes.clear();
savedRemarks.clear();

try {

android.content.SharedPreferences sp =
MainActivity.this.getSharedPreferences(
"network_storage",
android.content.Context.MODE_PRIVATE
);

String raw =
sp.getString("connections", "");

if (raw == null || raw.length() == 0)
return;

org.json.JSONArray array =
new org.json.JSONArray(raw);

for (int i = 0; i < array.length(); i++) {

org.json.JSONObject o =
array.getJSONObject(i);

savedHosts.add(
o.optString("host", "")
);

savedUsers.add(
o.optString("user", "")
);

savedPasswords.add(
o.optString("pass", "")
);

savedPorts.add(
o.optInt("port", 21)
);

savedTypes.add(
o.optInt("type", 0)
);

savedRemarks.add(
o.optString("remark", "")
);
}

} catch (Exception ignored) {
}
}


void connectFTP(final int index) {
    connectFTPPath(index, "");
}

void connectFTPPath(final int index, final String requestedPath) {
new Thread(new Runnable() {
@Override
public void run() {
try {
final String host = savedHosts.get(index);
final int port = savedPorts.get(index);
final String user = savedUsers.get(index);
final String pass = savedPasswords.get(index);
final int type = savedTypes.get(index);

final int targetSide = ACTIVE[0] ? 0 : 1;

if (!REMOTE_MODE[targetSide] && DIR[targetSide] != null) {
    REMOTE_LOCAL_PATH[targetSide] =
        DIR[targetSide].getAbsolutePath();
}

REMOTE_SUB[targetSide].clear();

MainActivity.this.runOnUiThread(new Runnable() {
@Override
public void run() {
android.widget.Toast.makeText(
MainActivity.this,
"جاري الاتصال بـ " + host + "...",
android.widget.Toast.LENGTH_SHORT
).show();
}
});

final java.util.ArrayList<String> names =
new java.util.ArrayList<String>();

String finalPath = requestedPath == null
? ""
: requestedPath.trim();

if (type == 0) {

org.apache.commons.net.ftp.FTPClient ftp =
new org.apache.commons.net.ftp.FTPClient();

ftp.connect(host, port);

int reply = ftp.getReplyCode();

if (!org.apache.commons.net.ftp.FTPReply.isPositiveCompletion(reply)) {
try { ftp.disconnect(); } catch (Exception ignored) {}
throw new Exception("FTP: Connection refused");
}

boolean loggedIn = ftp.login(user, pass);

if (!loggedIn) {
try { ftp.disconnect(); } catch (Exception ignored) {}
throw new Exception("FTP: Login failed");
}

ftp.enterLocalPassiveMode();

if (finalPath.length() > 0) {
if (!ftp.changeWorkingDirectory(finalPath)) {
try { ftp.disconnect(); } catch (Exception ignored) {}
throw new Exception("المجلد غير موجود: " + finalPath);
}
} else {
finalPath = ftp.printWorkingDirectory();
if (finalPath == null) finalPath = "/";
}

org.apache.commons.net.ftp.FTPFile[] files =
ftp.listFiles();

for (int i = 0; i < files.length; i++) {
if (files[i].getName().equals(".") ||
files[i].getName().equals(".."))
continue;

String remoteName = files[i].getName();

names.add(
    files[i].isDirectory()
    ? remoteName + "/"
    : remoteName
);

if (files[i].isDirectory()) {
    try {
        org.apache.commons.net.ftp.FTPFile[] inside =
            ftp.listFiles(
                (finalPath.endsWith("/") ? finalPath : finalPath + "/")
                + remoteName
            );
        REMOTE_SUB[targetSide].put(
            remoteName,
            inside == null ? "مجلد فارغ" : inside.length + " عنصر"
        );
    } catch (Exception ignored) {
        REMOTE_SUB[targetSide].put(remoteName, "مجلد");
    }
} else {
    REMOTE_SUB[targetSide].put(
        remoteName,
        size(files[i].getSize())
    );
}
}

try { ftp.disconnect(); } catch (Exception ignored) {}

final String shownPath = finalPath;

MainActivity.this.runOnUiThread(new Runnable() {
@Override
public void run() {
int side = ACTIVE[0] ? 0 : 1;

REMOTE_MODE[side] = true;
REMOTE_INDEX[side] = index;
REMOTE_PATH[side] = shownPath;
SHOW_HIDDEN[side] = false;

loadFTPList(
side,
names,
index,
shownPath
);


}
});

} else if (type == 1) {

com.jcraft.jsch.JSch jsch =
new com.jcraft.jsch.JSch();

com.jcraft.jsch.Session session =
jsch.getSession(user, host, port);

session.setPassword(pass);
java.util.Properties sftpConfig =
new java.util.Properties();
sftpConfig.put("StrictHostKeyChecking", "no");
sftpConfig.put("PreferredAuthentications", "password");
session.setConfig(sftpConfig);
session.connect(15000);

com.jcraft.jsch.ChannelSftp channelSftp =
(com.jcraft.jsch.ChannelSftp)
session.openChannel("sftp");

channelSftp.connect();

if (finalPath.length() > 0) {
channelSftp.cd(finalPath);
} else {
finalPath = channelSftp.pwd();
if (finalPath == null) finalPath = "/";
}

java.util.Vector<?> files =
channelSftp.ls(".");

for (int i = 0; i < files.size(); i++) {

com.jcraft.jsch.ChannelSftp.LsEntry entry =
(com.jcraft.jsch.ChannelSftp.LsEntry)
files.get(i);

if (entry.getFilename().equals(".") ||
entry.getFilename().equals(".."))
continue;

String remoteName = entry.getFilename();

names.add(
    entry.getAttrs().isDir()
    ? remoteName + "/"
    : remoteName
);

if (entry.getAttrs().isDir()) {
    try {
        java.util.Vector<?> inside =
            channelSftp.ls(
                finalPath.equals("/")
                ? "/" + remoteName
                : finalPath + "/" + remoteName
            );
        REMOTE_SUB[targetSide].put(
            remoteName,
            inside == null ? "مجلد فارغ" : inside.size() + " عنصر"
        );
    } catch (Exception ignored) {
        REMOTE_SUB[targetSide].put(remoteName, "مجلد");
    }
} else {
    REMOTE_SUB[targetSide].put(
        remoteName,
        size(entry.getAttrs().getSize())
    );
}
}

String shownPath = finalPath;

channelSftp.disconnect();
session.disconnect();

final String pathForUi = shownPath;

MainActivity.this.runOnUiThread(new Runnable() {
@Override
public void run() {
int side = ACTIVE[0] ? 0 : 1;

REMOTE_MODE[side] = true;
REMOTE_INDEX[side] = index;
REMOTE_PATH[side] = pathForUi;
SHOW_HIDDEN[side] = false;

loadFTPList(
side,
names,
index,
pathForUi
);


}
});

} else {

org.apache.commons.net.ftp.FTPSClient ftps =
new org.apache.commons.net.ftp.FTPSClient(
port == 990
);

ftps.setConnectTimeout(10000);
ftps.connect(host, port);

int reply = ftps.getReplyCode();

if (!org.apache.commons.net.ftp.FTPReply.isPositiveCompletion(reply)) {
try { ftps.disconnect(); } catch (Exception ignored) {}
throw new Exception("FTPS: Connection refused");
}

boolean loggedIn = ftps.login(user, pass);

if (!loggedIn) {
try { ftps.disconnect(); } catch (Exception ignored) {}
throw new Exception("FTPS: Login failed");
}

ftps.execPBSZ(0);
ftps.execPROT("P");
ftps.enterLocalPassiveMode();

if (finalPath.length() > 0) {

if (!ftps.changeWorkingDirectory(finalPath)) {
try { ftps.logout(); } catch (Exception ignored) {}
try { ftps.disconnect(); } catch (Exception ignored) {}
throw new Exception("المجلد غير موجود: " + finalPath);
}

} else {

finalPath = ftps.printWorkingDirectory();

if (finalPath == null)
finalPath = "/";
}

org.apache.commons.net.ftp.FTPFile[] files =
ftps.listFiles();

for (int i = 0; i < files.length; i++) {

if (files[i].getName().equals(".") ||
files[i].getName().equals(".."))
continue;

String remoteName = files[i].getName();

names.add(
    files[i].isDirectory()
    ? remoteName + "/"
    : remoteName
);

if (files[i].isDirectory()) {
    try {
        org.apache.commons.net.ftp.FTPFile[] inside =
            ftps.listFiles(
                (finalPath.endsWith("/") ? finalPath : finalPath + "/")
                + remoteName
            );
        REMOTE_SUB[targetSide].put(
            remoteName,
            inside == null ? "مجلد فارغ" : inside.length + " عنصر"
        );
    } catch (Exception ignored) {
        REMOTE_SUB[targetSide].put(remoteName, "مجلد");
    }
} else {
    REMOTE_SUB[targetSide].put(
        remoteName,
        size(files[i].getSize())
    );
}
}

try { ftps.logout(); } catch (Exception ignored) {}
try { ftps.disconnect(); } catch (Exception ignored) {}

final String shownPath = finalPath;

MainActivity.this.runOnUiThread(new Runnable() {
@Override
public void run() {

int side = ACTIVE[0] ? 0 : 1;

REMOTE_MODE[side] = true;
REMOTE_INDEX[side] = index;
REMOTE_PATH[side] = shownPath;
SHOW_HIDDEN[side] = false;

loadFTPList(
side,
names,
index,
shownPath
);


}
});
}

} catch (final Exception e) {

MainActivity.this.runOnUiThread(new Runnable() {
@Override
public void run() {
android.widget.Toast.makeText(
MainActivity.this,
"❌ " + e.getClass().getSimpleName() +
": " + e.getMessage(),
android.widget.Toast.LENGTH_LONG
).show();
}
});

}
}
}).start();
}

void loadFTPList(
final int side,
final java.util.ArrayList<String> fileNames,
final int connectionIndex,
final String currentPath
) {

LIST[side].removeAllViews();

REMOTE_MODE[side] = true;
REMOTE_INDEX[side] = connectionIndex;
REMOTE_PATH[side] = currentPath == null ? "" : currentPath;

TOP_PATH.setText(
REMOTE_PATH[side].length() == 0
? "/"
: REMOTE_PATH[side]
);

TOP_COUNT.setText(
(fileNames == null ? 0 : fileNames.size()) +
" عنصر"
);

if (REMOTE_PATH[side] == null ||
    REMOTE_PATH[side].length() == 0 ||
    REMOTE_PATH[side].equals("/")) {
    UP.setText("🚪");
} else {
    UP.setText("↑");
}

if (currentPath != null) {

android.widget.TextView back =
new android.widget.TextView(MainActivity.this);

back.setText(
    (currentPath == null ||
     currentPath.length() == 0 ||
     currentPath.equals("/"))
    ? "🚪  خروج من الاستضافة"
    : "↩  المجلد السابق"
);
back.setTextColor(WHITE);
back.setTextSize(14);
back.setGravity(android.view.Gravity.CENTER_VERTICAL);
back.setPadding(18 * DP, 0, 18 * DP, 0);

android.graphics.drawable.GradientDrawable backBg =
new android.graphics.drawable.GradientDrawable();

backBg.setColor(CARD2);
backBg.setCornerRadius(16 * DP);
back.setBackground(backBg);

android.widget.LinearLayout.LayoutParams backLp =
new android.widget.LinearLayout.LayoutParams(-1, 52 * DP);

backLp.setMargins(0, 4 * DP, 0, 8 * DP);

LIST[side].addView(back, backLp);

back.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {

String p = REMOTE_PATH[side];

if (p == null || p.length() == 0 || p.equals("/")) {

    REMOTE_MODE[side] = false;
    REMOTE_INDEX[side] = -1;
    REMOTE_PATH[side] = "";

    java.io.File local =
        new java.io.File(
            REMOTE_LOCAL_PATH[side] == null
            ? STORAGE.getAbsolutePath()
            : REMOTE_LOCAL_PATH[side]
        );

    if (!local.exists() || !local.isDirectory())
        local = STORAGE;

    DIR[side] = local;
    HISTORY[side].clear();
    FUTURE[side].clear();
    SEARCHING[side] = false;
    SEARCH_RESULTS[side].clear();

    load(side);
    return;
}

String parent = p;

while (parent.endsWith("/") && parent.length() > 1)
parent = parent.substring(0, parent.length() - 1);

int slash = parent.lastIndexOf('/');

if (slash <= 0) {
parent = "/";
} else {
parent = parent.substring(0, slash);
}

connectFTPPath(
REMOTE_INDEX[side],
parent
);
}
});

}

if (fileNames == null || fileNames.size() == 0) {

android.widget.TextView empty =
new android.widget.TextView(MainActivity.this);

empty.setText("📂\n\nالمجلد فارغ");
empty.setTextColor(GRAY);
empty.setTextSize(18);
empty.setGravity(android.view.Gravity.CENTER);

LIST[side].addView(
empty,
new android.widget.LinearLayout.LayoutParams(
-1,
250
)
);

return;
}

for (int i = 0; i < fileNames.size(); i++) {

final String name = fileNames.get(i);
final boolean isDir = name.endsWith("/");

android.widget.LinearLayout item =
new android.widget.LinearLayout(MainActivity.this);

item.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.setPadding(
    7 * DP,
    2 * DP,
    3 * DP,
    2 * DP
);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setColor(CARD);
bg.setCornerRadius(18);
item.setBackground(bg);

android.widget.LinearLayout.LayoutParams lp =
new android.widget.LinearLayout.LayoutParams(-1, 52 * DP);

lp.setMargins(0, 3 * DP, 0, 3 * DP);

LIST[side].addView(item, lp);

android.widget.TextView icon =
new android.widget.TextView(MainActivity.this);

icon.setText(isDir ? "📁" : "📄");
icon.setTextColor(isDir ? BLUE : GRAY);
icon.setTextSize(19);
icon.setGravity(android.view.Gravity.CENTER);

item.addView(
icon,
new android.widget.LinearLayout.LayoutParams(
44 * DP,
48 * DP
)
);

android.widget.TextView fileName =
new android.widget.TextView(MainActivity.this);

final String displayName =
isDir
? name.substring(0, name.length() - 1)
: name;

fileName.setText(displayName);
fileName.setTextColor(WHITE);
fileName.setTextSize(13);
fileName.setSingleLine(true);
fileName.setEllipsize(
android.text.TextUtils.TruncateAt.END
);

android.widget.LinearLayout textBox =
new android.widget.LinearLayout(MainActivity.this);

textBox.setOrientation(
    android.widget.LinearLayout.VERTICAL
);
textBox.setGravity(
    android.view.Gravity.CENTER_VERTICAL
);

item.removeView(fileName);
textBox.addView(
    fileName,
    new android.widget.LinearLayout.LayoutParams(
        -1,
        -2
    )
);

android.widget.TextView sub =
new android.widget.TextView(MainActivity.this);

String subText =
    REMOTE_SUB[side].containsKey(displayName)
    ? REMOTE_SUB[side].get(displayName)
    : "";

if (subText.length() == 0)
    subText = isDir ? "مجلد" : "ملف";

sub.setText(subText);
sub.setTextColor(GRAY);
sub.setTextSize(9);
sub.setSingleLine(true);
sub.setEllipsize(
    android.text.TextUtils.TruncateAt.END
);

textBox.addView(
    sub,
    new android.widget.LinearLayout.LayoutParams(
        -1,
        -2
    )
);

item.addView(
    textBox,
    new android.widget.LinearLayout.LayoutParams(
        0,
        -1,
        1
    )
);

item.setOnClickListener(
new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {

if (isDir) {

String base = REMOTE_PATH[side];

if (base == null || base.length() == 0)
base = "/";

String next;

if (base.equals("/")) {
next = "/" + displayName;
} else {
next =
base.endsWith("/")
? base + displayName
: base + "/" + displayName;
}

connectFTPPath(
REMOTE_INDEX[side],
next
);

} else {
    openRemoteExternal(side, displayName);
}
}
}
);

}

}

void remoteBack(final int side) {

if (!REMOTE_MODE[side])
return;

String p = REMOTE_PATH[side];

if (p == null || p.length() == 0 || p.equals("/")) {

    REMOTE_MODE[side] = false;
    REMOTE_INDEX[side] = -1;
    REMOTE_PATH[side] = "";

    java.io.File local =
        new java.io.File(
            REMOTE_LOCAL_PATH[side] == null
            ? STORAGE.getAbsolutePath()
            : REMOTE_LOCAL_PATH[side]
        );

    if (!local.exists() || !local.isDirectory())
        local = STORAGE;

    DIR[side] = local;
    HISTORY[side].clear();
    FUTURE[side].clear();
    SEARCHING[side] = false;
    SEARCH_RESULTS[side].clear();

    load(side);
    return;
}

String parent = p;

while (parent.endsWith("/") && parent.length() > 1)
parent = parent.substring(0, parent.length() - 1);

int slash = parent.lastIndexOf('/');

if (slash <= 0)
parent = "/";
else
parent = parent.substring(0, slash);

connectFTPPath(
REMOTE_INDEX[side],
parent
);
}

void addNetworkItem(final int side, final int index) {
android.widget.LinearLayout item = new android.widget.LinearLayout(MainActivity.this);
item.setGravity(android.view.Gravity.CENTER_VERTICAL);
item.setPadding(7, 4, 3, 4);

android.graphics.drawable.GradientDrawable bg = new android.graphics.drawable.GradientDrawable();  
bg.setColor(CARD);  
bg.setCornerRadius(18);  
item.setBackground(bg);  

android.widget.LinearLayout.LayoutParams lp = new android.widget.LinearLayout.LayoutParams(-1, 60);  
lp.setMargins(0, 3, 0, 3);  
LIST[side].addView(item, lp);  

android.widget.TextView icon = new android.widget.TextView(MainActivity.this);  
int type = savedTypes.get(index);  
if (type == 0) icon.setText("FTP");  
else if (type == 1) icon.setText("SFTP");  
else icon.setText("FTPS");  
icon.setTextColor(BLUE);  
icon.setTextSize(14);  
icon.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);  
icon.setGravity(android.view.Gravity.CENTER);  
item.addView(icon, new android.widget.LinearLayout.LayoutParams(50, 55));  

android.widget.TextView name = new android.widget.TextView(MainActivity.this);  
name.setText(savedHosts.get(index));  
name.setTextColor(WHITE);  
name.setTextSize(13);  
name.setSingleLine(true);  
name.setEllipsize(android.text.TextUtils.TruncateAt.END);  
item.addView(name, new android.widget.LinearLayout.LayoutParams(0, -1, 1));  

item.setOnClickListener(new android.view.View.OnClickListener() {  
    @Override  
    public void onClick(android.view.View v) {  
        connectFTP(index);  
    }  
});

}

void showFTPDialog(final int type) {
final String[] types = {"FTP", "SFTP", "FTPS"};
String titleText = "Add " + types[type];
final android.app.Dialog dialog = new android.app.Dialog(MainActivity.this);
dialog.requestWindowFeature(android.view.Window.FEATURE_NO_TITLE);

android.widget.ScrollView scroll = new android.widget.ScrollView(MainActivity.this);  
scroll.setFillViewport(true);  

android.widget.LinearLayout root = new android.widget.LinearLayout(MainActivity.this);  
root.setOrientation(android.widget.LinearLayout.VERTICAL);  
root.setPadding(24 * DP, 24 * DP, 24 * DP, 24 * DP);  

android.graphics.drawable.GradientDrawable rootBg = new android.graphics.drawable.GradientDrawable();  
rootBg.setColor(android.graphics.Color.rgb(35, 39, 46));  
rootBg.setCornerRadius(24 * DP);  
root.setBackground(rootBg);  

scroll.addView(root);  
dialog.setContentView(scroll);  

android.widget.LinearLayout header = new android.widget.LinearLayout(MainActivity.this);  
header.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
header.setGravity(android.view.Gravity.CENTER_VERTICAL);  

android.widget.TextView title = new android.widget.TextView(MainActivity.this);  
title.setText(titleText);  
title.setTextColor(WHITE);  
title.setTextSize(20);  
title.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);  

android.widget.LinearLayout.LayoutParams titleParams = new android.widget.LinearLayout.LayoutParams(0, -2, 1);  
header.addView(title, titleParams);  

android.widget.TextView closeBtn = new android.widget.TextView(MainActivity.this);  
closeBtn.setText("✕");  
closeBtn.setTextColor(GRAY);  
closeBtn.setTextSize(22);  
closeBtn.setGravity(android.view.Gravity.CENTER);  
closeBtn.setPadding(10 * DP, 0, 0, 0);  
closeBtn.setOnClickListener(new android.view.View.OnClickListener() {  
    @Override  
    public void onClick(android.view.View v) {  
        dialog.dismiss();  
    }  
});  
header.addView(closeBtn, new android.widget.LinearLayout.LayoutParams(44 * DP, 44 * DP));  
root.addView(header, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.widget.Space space = new android.widget.Space(MainActivity.this);  
root.addView(space, new android.widget.LinearLayout.LayoutParams(1, 16 * DP));  

android.widget.TextView hostLabel = new android.widget.TextView(MainActivity.this);  
hostLabel.setText("Host");  
hostLabel.setTextColor(GRAY);  
hostLabel.setTextSize(12);  
root.addView(hostLabel, new android.widget.LinearLayout.LayoutParams(-1, -2));  

final android.widget.EditText hostInput = createEditText("Host");  
root.addView(hostInput, new android.widget.LinearLayout.LayoutParams(-1, 48 * DP));  

android.widget.Space space2 = new android.widget.Space(MainActivity.this);  
root.addView(space2, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));  

android.widget.LinearLayout portRow = new android.widget.LinearLayout(MainActivity.this);  
portRow.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
portRow.setGravity(android.view.Gravity.CENTER_VERTICAL);  

android.widget.TextView portLabel = new android.widget.TextView(MainActivity.this);  
portLabel.setText("Port");  
portLabel.setTextColor(GRAY);  
portLabel.setTextSize(12);  
portRow.addView(portLabel, new android.widget.LinearLayout.LayoutParams(0, -2, 1));  

final android.widget.EditText portInput = new android.widget.EditText(MainActivity.this);  
if (type == 0) portInput.setText("21");  
else if (type == 1) portInput.setText("22");  
else portInput.setText("990");  
portInput.setSingleLine(true);  
portInput.setTextColor(WHITE);  
portInput.setTextSize(15);  
portInput.setGravity(android.view.Gravity.RIGHT);  
portInput.setPadding(0, 12 * DP, 0, 12 * DP);  

android.graphics.drawable.GradientDrawable portBg = new android.graphics.drawable.GradientDrawable();  
portBg.setColor(android.graphics.Color.rgb(20, 24, 30));  
portBg.setCornerRadius(14 * DP);  
portBg.setStroke(1 * DP, android.graphics.Color.rgb(55, 63, 75));  
portInput.setBackground(portBg);  

android.widget.LinearLayout.LayoutParams portParams = new android.widget.LinearLayout.LayoutParams(80 * DP, 48 * DP);  
portRow.addView(portInput, portParams);  
root.addView(portRow, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.widget.Space space3 = new android.widget.Space(MainActivity.this);  
root.addView(space3, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));  

android.widget.TextView userLabel = new android.widget.TextView(MainActivity.this);  
userLabel.setText("Username");  
userLabel.setTextColor(GRAY);  
userLabel.setTextSize(12);  
root.addView(userLabel, new android.widget.LinearLayout.LayoutParams(-1, -2));  

final android.widget.EditText userInput = createEditText("anonymous");  
root.addView(userInput, new android.widget.LinearLayout.LayoutParams(-1, 48 * DP));  

android.widget.Space space4 = new android.widget.Space(MainActivity.this);  
root.addView(space4, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));  

android.widget.TextView passLabel = new android.widget.TextView(MainActivity.this);  
passLabel.setText("Password");  
passLabel.setTextColor(GRAY);  
passLabel.setTextSize(12);  
root.addView(passLabel, new android.widget.LinearLayout.LayoutParams(-1, -2));  

final android.widget.EditText passInput = createEditText("Optional");  
passInput.setInputType(android.text.InputType.TYPE_CLASS_TEXT | android.text.InputType.TYPE_TEXT_VARIATION_PASSWORD);  
root.addView(passInput, new android.widget.LinearLayout.LayoutParams(-1, 48 * DP));  

android.widget.Space space5 = new android.widget.Space(MainActivity.this);  
root.addView(space5, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));  

android.widget.TextView pathLabel = new android.widget.TextView(MainActivity.this);  
pathLabel.setText("Initial Path");  
pathLabel.setTextColor(GRAY);  
pathLabel.setTextSize(12);  
root.addView(pathLabel, new android.widget.LinearLayout.LayoutParams(-1, -2));  

final android.widget.EditText pathInput = createEditText("Optional");  
root.addView(pathInput, new android.widget.LinearLayout.LayoutParams(-1, 48 * DP));  

android.widget.Space space6 = new android.widget.Space(MainActivity.this);  
root.addView(space6, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));  

android.widget.TextView remarkLabel = new android.widget.TextView(MainActivity.this);  
remarkLabel.setText("Remark");  
remarkLabel.setTextColor(GRAY);  
remarkLabel.setTextSize(12);  
root.addView(remarkLabel, new android.widget.LinearLayout.LayoutParams(-1, -2));  

final android.widget.EditText remarkInput = createEditText("");  
root.addView(remarkInput, new android.widget.LinearLayout.LayoutParams(-1, 48 * DP));  

android.widget.Space space7 = new android.widget.Space(MainActivity.this);  
root.addView(space7, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));  

android.widget.LinearLayout charsetRow = new android.widget.LinearLayout(MainActivity.this);  
charsetRow.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
charsetRow.setGravity(android.view.Gravity.CENTER_VERTICAL);  

android.widget.TextView charsetLabel = new android.widget.TextView(MainActivity.this);  
charsetLabel.setText("Charset");  
charsetLabel.setTextColor(GRAY);  
charsetLabel.setTextSize(12);  
charsetRow.addView(charsetLabel, new android.widget.LinearLayout.LayoutParams(0, -2, 1));  

final android.widget.Spinner charsetSpinner = new android.widget.Spinner(MainActivity.this);  
String[] charsets = {"UTF-8", "GBK", "ISO-8859-1"};  
android.widget.ArrayAdapter<String> charsetAdapter = new android.widget.ArrayAdapter<String>(MainActivity.this, android.R.layout.simple_spinner_item, charsets);  
charsetAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);  
charsetSpinner.setAdapter(charsetAdapter);  
charsetRow.addView(charsetSpinner, new android.widget.LinearLayout.LayoutParams(120 * DP, 40 * DP));  
root.addView(charsetRow, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.widget.Space space8 = new android.widget.Space(MainActivity.this);  
root.addView(space8, new android.widget.LinearLayout.LayoutParams(1, 16 * DP));  

String[] checks = {"Enable multi-threaded transfer", "Sync file permissions on transfer", "Hide address in sidebar"};  
for (String check : checks) {  
    android.widget.CheckBox cb = new android.widget.CheckBox(MainActivity.this);  
    cb.setText(check);  
    cb.setTextColor(WHITE);  
    cb.setTextSize(13);  
    root.addView(cb, new android.widget.LinearLayout.LayoutParams(-1, 40 * DP));  
}  

android.widget.Space space9 = new android.widget.Space(MainActivity.this);  
root.addView(space9, new android.widget.LinearLayout.LayoutParams(1, 8 * DP));  

android.widget.LinearLayout transferRow = new android.widget.LinearLayout(MainActivity.this);  
transferRow.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
transferRow.setGravity(android.view.Gravity.CENTER_VERTICAL);  

android.widget.TextView transferLabel = new android.widget.TextView(MainActivity.this);  
transferLabel.setText("Transfer mode");  
transferLabel.setTextColor(GRAY);  
transferLabel.setTextSize(12);  
transferRow.addView(transferLabel, new android.widget.LinearLayout.LayoutParams(0, -2, 1));  

final android.widget.Spinner transferSpinner = new android.widget.Spinner(MainActivity.this);  
String[] modes = {"Active", "Passive"};  
android.widget.ArrayAdapter<String> transferAdapter = new android.widget.ArrayAdapter<String>(MainActivity.this, android.R.layout.simple_spinner_item, modes);  
transferAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);  
transferSpinner.setAdapter(transferAdapter);  
transferRow.addView(transferSpinner, new android.widget.LinearLayout.LayoutParams(120 * DP, 40 * DP));  
root.addView(transferRow, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.widget.Space space10 = new android.widget.Space(MainActivity.this);  
root.addView(space10, new android.widget.LinearLayout.LayoutParams(1, 20 * DP));  

android.widget.LinearLayout buttons = new android.widget.LinearLayout(MainActivity.this);  
buttons.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
buttons.setGravity(android.view.Gravity.CENTER);  

String[] btnNames = {"TEST", "CANCEL", "SAVE"};  
int[] btnColors = {android.graphics.Color.rgb(60, 65, 75), android.graphics.Color.rgb(45, 50, 58), BLUE};  

for (int i = 0; i < btnNames.length; i++) {  
    android.widget.TextView btn = new android.widget.TextView(MainActivity.this);  
    btn.setText(btnNames[i]);  
    btn.setTextColor(WHITE);  
    btn.setTextSize(13);  
    btn.setTypeface(android.graphics.Typeface.DEFAULT, i == 2 ? android.graphics.Typeface.BOLD : android.graphics.Typeface.NORMAL);  
    btn.setGravity(android.view.Gravity.CENTER);  
    btn.setPadding(20 * DP, 10 * DP, 20 * DP, 10 * DP);  

    android.graphics.drawable.GradientDrawable btnBg = new android.graphics.drawable.GradientDrawable();  
    btnBg.setColor(btnColors[i]);  
    btnBg.setCornerRadius(14 * DP);  
    btn.setBackground(btnBg);  

    if (i == 1) {  
        btn.setOnClickListener(new android.view.View.OnClickListener() {  
            @Override  
            public void onClick(android.view.View v) {  
                dialog.dismiss();  
            }  
        });  
    } else if (i == 0) {  
        btn.setOnClickListener(new android.view.View.OnClickListener() {  
            @Override  
            public void onClick(android.view.View v) {  
                final String host = hostInput.getText().toString().trim();  
                if (host.length() == 0) {  
                    android.widget.Toast.makeText(MainActivity.this, "Enter host first", android.widget.Toast.LENGTH_SHORT).show();  
                    return;  
                }  
                final int port = Integer.parseInt(portInput.getText().toString());  
                final String user = userInput.getText().toString().trim();  
                final String pass = passInput.getText().toString();  
                final int finalType = type;  
                  
                new Thread(new Runnable() {  
                    @Override  
                    public void run() {  
                        try {  
                            if (finalType == 0) {  
                                org.apache.commons.net.ftp.FTPClient ftp = new org.apache.commons.net.ftp.FTPClient();  
                                ftp.connect(host, port);  
                                int reply = ftp.getReplyCode();  
                                if (!org.apache.commons.net.ftp.FTPReply.isPositiveCompletion(reply)) {  
                                    ftp.disconnect();  
                                    MainActivity.this.runOnUiThread(new Runnable() {  
                                        @Override  
                                        public void run() {  
                                            android.widget.Toast.makeText(MainActivity.this, "❌ FTP: Connection refused", android.widget.Toast.LENGTH_SHORT).show();  
                                        }  
                                    });  
                                    return;  
                                }  
                                boolean loggedIn = ftp.login(user, pass);  
                                ftp.disconnect();  
                                final String msg = loggedIn ? "✅ FTP: Connection successful" : "❌ FTP: Login failed";  
                                MainActivity.this.runOnUiThread(new Runnable() {  
                                    @Override  
                                    public void run() {  
                                        android.widget.Toast.makeText(MainActivity.this, msg, android.widget.Toast.LENGTH_SHORT).show();  
                                    }  
                                });  
                            } else if (finalType == 1) {  
                                com.jcraft.jsch.JSch jsch = new com.jcraft.jsch.JSch();  
                                com.jcraft.jsch.Session session = jsch.getSession(user, host, port);  
                                session.setPassword(pass);  
                                java.util.Properties sftpConfig =  
                                new java.util.Properties();  
                                sftpConfig.put("StrictHostKeyChecking", "no");  
                                sftpConfig.put("PreferredAuthentications", "password");  
                                session.setConfig(sftpConfig);  
                                session.connect(15000);  
                                session.disconnect();  
                                MainActivity.this.runOnUiThread(new Runnable() {  
                                    @Override  
                                    public void run() {  
                                        android.widget.Toast.makeText(MainActivity.this, "✅ SFTP: Connection successful", android.widget.Toast.LENGTH_SHORT).show();  
                                    }  
                                });  
                            } else {  
                                java.net.URL url = new java.net.URL("ftps://" + user + ":" + pass + "@" + host + ":" + port + "/");  
                                java.net.URLConnection conn = url.openConnection();  
                                conn.setConnectTimeout(10000);  
                                conn.connect();  
                                MainActivity.this.runOnUiThread(new Runnable() {  
                                    @Override  
                                    public void run() {  
                                        android.widget.Toast.makeText(MainActivity.this, "✅ FTPS: Connection successful", android.widget.Toast.LENGTH_SHORT).show();  
                                    }  
                                });  
                            }  
                        } catch (final Exception e) {  
                            MainActivity.this.runOnUiThread(new Runnable() {  
                                @Override  
                                public void run() {  
                                    android.widget.Toast.makeText(MainActivity.this, "❌ " + e.getClass().getSimpleName() + ": " + e.getMessage(), android.widget.Toast.LENGTH_SHORT).show();  
                                }  
                            });  
                        }  
                    }  
                }).start();  
            }  
        });  
    } else {  
        btn.setOnClickListener(new android.view.View.OnClickListener() {  
            @Override  
            public void onClick(android.view.View v) {  
                String host = hostInput.getText().toString().trim();  
                if (host.length() == 0) {  
                    android.widget.Toast.makeText(MainActivity.this, "Enter host first", android.widget.Toast.LENGTH_SHORT).show();  
                    return;  
                }  
                savedHosts.add(host);  
                savedUsers.add(userInput.getText().toString().trim());  
                savedPasswords.add(passInput.getText().toString());  
                savedPorts.add(Integer.parseInt(portInput.getText().toString()));  
                savedTypes.add(type);  
                savedRemarks.add(remarkInput.getText().toString().trim());  

                saveConnections();

                android.widget.Toast.makeText(MainActivity.this, "✅ Saved " + types[type], android.widget.Toast.LENGTH_SHORT).show();  
                dialog.dismiss();  
                load(0);  
                load(1);  
            }  
        });  
    }  

    buttons.addView(btn, new android.widget.LinearLayout.LayoutParams(0, 48 * DP, 1));  
    if (i < 2) {  
        android.widget.Space btnSpace = new android.widget.Space(MainActivity.this);  
        buttons.addView(btnSpace, new android.widget.LinearLayout.LayoutParams(10 * DP, 1));  
    }  
}  

root.addView(buttons, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.view.Window window = dialog.getWindow();  
if (window != null) {  
    window.setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));  
    window.setDimAmount(0.7f);  
    window.addFlags(android.view.WindowManager.LayoutParams.FLAG_DIM_BEHIND);  
    window.setLayout((int)(340 * DP), android.view.WindowManager.LayoutParams.WRAP_CONTENT);  
    window.setGravity(android.view.Gravity.CENTER);  
}  

dialog.show();

}

android.widget.EditText createEditText(String hint) {
android.widget.EditText et = new android.widget.EditText(MainActivity.this);
et.setHint(hint);
et.setSingleLine(true);
et.setTextColor(WHITE);
et.setHintTextColor(GRAY);
et.setTextSize(15);
et.setPadding(16 * DP, 12 * DP, 16 * DP, 12 * DP);

android.graphics.drawable.GradientDrawable bg = new android.graphics.drawable.GradientDrawable();  
bg.setColor(android.graphics.Color.rgb(20, 24, 30));  
bg.setCornerRadius(14 * DP);  
bg.setStroke(1 * DP, android.graphics.Color.rgb(55, 63, 75));  
et.setBackground(bg);  

return et;

}

android.widget.PopupWindow TOP_POPUP;

android.app.Dialog progressDialog;
android.widget.TextView progressText;
android.widget.ProgressBar progressBar;
boolean progressHidden = false;

void activate(int side) {

ACTIVE[0] =
side == 0;

ACTIVE[1] =
side == 1;

BOX[0].setBackgroundColor(
side == 0
? android.graphics.Color.rgb(15, 18, 23)
: BG
);

BOX[1].setBackgroundColor(
side == 1
? android.graphics.Color.rgb(15, 18, 23)
: BG
);

if (REMOTE_MODE[side]) {

TOP_PATH.setText(
REMOTE_PATH[side] == null ||
REMOTE_PATH[side].length() == 0
? "/"
: REMOTE_PATH[side]
);

if (REMOTE_PATH[side] == null ||
    REMOTE_PATH[side].length() == 0 ||
    REMOTE_PATH[side].equals("/")) {
    UP.setText("🚪");
} else {
    UP.setText("↑");
}

return;
}

UP.setText("↑");

if (SEARCHING[side]) {

TOP_PATH.setText(      
    "بحث"      
);      

TOP_COUNT.setText(      
    SEARCH_RESULTS[side].size()      
    + " نتيجة"      
);

} else {

TOP_PATH.setText(      
    DIR[side].getAbsolutePath()      
);      

java.io.File[] a =      
DIR[side].listFiles();      

TOP_COUNT.setText(      
    a == null      
    ? "لا يمكن الوصول"      
    : a.length + " عنصر"      
);

}

}

boolean hidden(
java.io.File f
) {

return f.getName().startsWith(".");

}

String icon(
java.io.File f
) {

if (f.isDirectory())
return "▰";

String n =
f.getName()
.toLowerCase();

if (
n.endsWith(".jpg") ||
n.endsWith(".jpeg") ||
n.endsWith(".png") ||
n.endsWith(".webp") ||
n.endsWith(".gif")
)
return "▧";

if (
n.endsWith(".mp3") ||
n.endsWith(".wav") ||
n.endsWith(".m4a")
)
return "♫";

if (
n.endsWith(".mp4") ||
n.endsWith(".mkv") ||
n.endsWith(".avi")
)
return "▶";

if (
n.endsWith(".zip") ||
n.endsWith(".rar") ||
n.endsWith(".7z")
)
return "▤";

if (n.endsWith(".apk"))
return "◆";

if (
n.endsWith(".php") ||
n.endsWith(".java") ||
n.endsWith(".py") ||
n.endsWith(".js") ||
n.endsWith(".html") ||
n.endsWith(".css") ||
n.endsWith(".xml") ||
n.endsWith(".json")
)
return "</>";

return "□";

}

void load(
final int side
) {

REMOTE_MODE[side] = false;
REMOTE_INDEX[side] = -1;
REMOTE_PATH[side] = "";

SEARCHING[side] = false;
SEARCH_RESULTS[side].clear();
LIST[side].removeAllViews();

activate(side);

java.io.File[] files =
DIR[side].listFiles();

if (files == null) {

TOP_COUNT.setText(      
    "لا يمكن الوصول"      
);      

return;

}

java.util.ArrayList<java.io.File> visible =
new java.util.ArrayList<java.io.File>();

for (
int i = 0;
i < files.length;
i++
) {

if (      
    !SHOW_HIDDEN[side] &&      
    hidden(files[i])      
)      
    continue;      

visible.add(files[i]);

}

java.util.Collections.sort(
visible,
new java.util.Comparator<java.io.File>() {

@Override      
    public int compare(      
        java.io.File a,      
        java.io.File b      
    ) {      

        if (      
            a.isDirectory() &&      
            !b.isDirectory()      
        )      
            return -1;      

        if (      
            !a.isDirectory() &&      
            b.isDirectory()      
        )      
            return 1;      

        return a.getName()      
        .toLowerCase()      
        .compareTo(      
            b.getName()      
            .toLowerCase()      
        );      
    }      
}

);

TOP_COUNT.setText(
visible.size()
+ " عنصر"
);

if (
!DIR[side]
.getAbsolutePath()
.equals(
STORAGE.getAbsolutePath()
)
) {

addBackItem(side);

}

for (
int i = 0;
i < visible.size();
i++
) {

addItem(      
    side,      
    visible.get(i)      
);

}

}

void addBackItem(
final int side
) {

android.widget.LinearLayout item =
new android.widget.LinearLayout(
MainActivity.this
);

item.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.setPadding(
8,
4,
5,
4
);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setColor(CARD2);
bg.setCornerRadius(18);

item.setBackground(bg);

android.widget.LinearLayout.LayoutParams lp =
new android.widget.LinearLayout.LayoutParams(
-1,
70
);

lp.setMargins(
0,
3,
0,
6
);

LIST[side].addView(
item,
lp
);

android.widget.TextView icon =
new android.widget.TextView(
MainActivity.this
);

icon.setText("↩");
icon.setTextColor(BLUE);
icon.setTextSize(27);
icon.setGravity(
android.view.Gravity.CENTER
);

item.addView(
icon,
new android.widget.LinearLayout.LayoutParams(
58,
64
)
);

android.widget.TextView name =
new android.widget.TextView(
MainActivity.this
);

name.setText(
"رجوع للمجلد السابق"
);

name.setTextColor(WHITE);
name.setTextSize(15);
name.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.addView(
name,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

item.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        parent(side);      
    }      
}

);

}

void addItem(
final int side,
final java.io.File f
) {

android.widget.LinearLayout item =
new android.widget.LinearLayout(
MainActivity.this
);

item.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.setPadding(
7,
4,
3,
4
);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setColor(CARD);
bg.setCornerRadius(18);

item.setBackground(bg);

android.widget.LinearLayout.LayoutParams lp =
new android.widget.LinearLayout.LayoutParams(
-1,
84
);

lp.setMargins(
0,
3,
0,
3
);

LIST[side].addView(
item,
lp
);

android.widget.TextView fileIcon =
new android.widget.TextView(
MainActivity.this
);

fileIcon.setText(
icon(f)
);

fileIcon.setTextColor(
f.isDirectory()
? BLUE
: GRAY
);

fileIcon.setTextSize(
f.isDirectory()
? 27
: 23
);

fileIcon.setGravity(
android.view.Gravity.CENTER
);

item.addView(
fileIcon,
new android.widget.LinearLayout.LayoutParams(
58,
72
)
);

android.widget.LinearLayout texts =
new android.widget.LinearLayout(
MainActivity.this
);

texts.setOrientation(
android.widget.LinearLayout.VERTICAL
);

texts.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.addView(
texts,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

android.widget.TextView name =
new android.widget.TextView(
MainActivity.this
);

name.setText(
f.getName()
);

name.setTextColor(WHITE);
name.setTextSize(15);
name.setSingleLine(true);

name.setEllipsize(
android.text.TextUtils.TruncateAt.END
);

texts.addView(name);

android.widget.TextView sub =
new android.widget.TextView(
MainActivity.this
);

if (f.isDirectory()) {

java.io.File[] a =      
f.listFiles();      

sub.setText(      
    a == null      
    ? "مجلد"      
    : a.length + " عنصر"      
);

} else {

sub.setText(      
    size(f.length())      
);

}

sub.setTextColor(GRAY);
sub.setTextSize(10);

texts.addView(sub);

android.widget.TextView dots =
new android.widget.TextView(
MainActivity.this
);

dots.setText("⋮");
dots.setTextColor(GRAY);
dots.setTextSize(23);
dots.setGravity(
android.view.Gravity.CENTER
);

item.addView(
dots,
new android.widget.LinearLayout.LayoutParams(
42,
70
)
);

item.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        activate(side);      

        if (f.isDirectory()) {      

            HISTORY[side].add(      
                DIR[side]      
                .getAbsolutePath()      
            );      

            FUTURE[side].clear();      

            DIR[side] = f;      

            load(side);      

        } else {      

            openEditor(f);      
        }      
    }      
}

);

item.setOnLongClickListener(
new android.view.View.OnLongClickListener() {

@Override      
    public boolean onLongClick(      
        android.view.View v      
    ) {      

        activate(side);      

        showActions(      
            side,      
            f      
        );      

        return true;      
    }      
}

);

}

void searchDialog(
final int side
) {

final android.widget.EditText input =
new android.widget.EditText(
MainActivity.this
);

input.setSingleLine(true);
input.setHint(
"اكتب اسم الملف أو المجلد"
);

final android.app.AlertDialog dialog =
new android.app.AlertDialog.Builder(
MainActivity.this
)
.setTitle("بحث")
.setView(input)
.setNegativeButton(
"إلغاء",
null
)
.setPositiveButton(
"بحث",
null
)
.create();

dialog.setOnShowListener(
new android.content.DialogInterface.OnShowListener() {

@Override      
    public void onShow(      
        android.content.DialogInterface d      
    ) {      

        dialog.getButton(      
            android.app.AlertDialog.BUTTON_POSITIVE      
        )      
        .setOnClickListener(      
            new android.view.View.OnClickListener() {      

                @Override      
                public void onClick(      
                    android.view.View v      
                ) {      

                    String q =      
                    input.getText()      
                    .toString()      
                    .trim()      
                    .toLowerCase();      

                    if (q.length() == 0)      
                        return;      

                    dialog.dismiss();      

                    search(      
                        side,      
                        q      
                    );      
                }      
            }      
        );      

        input.requestFocus();      

        dialog.getWindow()      
        .setSoftInputMode(      
            android.view.WindowManager      
            .LayoutParams      
            .SOFT_INPUT_STATE_ALWAYS_VISIBLE      
        );      
    }      
}

);

dialog.show();

}

void search(
final int side,
String query
) {

SEARCHING[side] = true;

SEARCH_RESULTS[side].clear();

findRecursive(
DIR[side],
query,
side
);

LIST[side].removeAllViews();

activate(side);

addSearchBack(side);

java.util.Collections.sort(
SEARCH_RESULTS[side],
new java.util.Comparator<java.io.File>() {

@Override      
    public int compare(      
        java.io.File a,      
        java.io.File b      
    ) {      

        return a.getName()      
        .toLowerCase()      
        .compareTo(      
            b.getName()      
            .toLowerCase()      
        );      
    }      
}

);

for (
int i = 0;
i < SEARCH_RESULTS[side].size();
i++
) {

addSearchItem(      
    side,      
    SEARCH_RESULTS[side].get(i)      
);

}

if (
SEARCH_RESULTS[side].size() == 0
) {

android.widget.TextView empty =      
new android.widget.TextView(      
    MainActivity.this      
);      

empty.setText(      
    "🔎\n\nلا توجد نتائج"      
);      

empty.setTextColor(GRAY);      
empty.setTextSize(18);      
empty.setGravity(      
    android.view.Gravity.CENTER      
);      

LIST[side].addView(      
    empty,      
    new android.widget.LinearLayout.LayoutParams(      
        -1,      
        250      
    )      
);

}

}

void findRecursive(
java.io.File dir,
String query,
int side
) {

java.io.File[] files =
dir.listFiles();

if (files == null)
return;

for (
int i = 0;
i < files.length;
i++
) {

java.io.File f =      
files[i];      

if (      
    !SHOW_HIDDEN[side] &&      
    hidden(f)      
)      
    continue;      

if (      
    f.getName()      
    .toLowerCase()      
    .contains(query)      
) {      

    SEARCH_RESULTS[side]      
    .add(f);      
}      

if (f.isDirectory()) {      

    findRecursive(      
        f,      
        query,      
        side      
    );      
}

}

}

void addSearchBack(
final int side
) {

android.widget.LinearLayout item =
new android.widget.LinearLayout(
MainActivity.this
);

item.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setColor(
android.graphics.Color.rgb(
40,
47,
60
)
);

bg.setCornerRadius(22);
item.setBackground(bg);

android.widget.LinearLayout.LayoutParams lp =
new android.widget.LinearLayout.LayoutParams(
-1,
76
);

lp.setMargins(
0,
3,
0,
8
);

LIST[side].addView(
item,
lp
);

android.widget.TextView ic =
new android.widget.TextView(
MainActivity.this
);

ic.setText("↩");
ic.setTextColor(BLUE);
ic.setTextSize(29);
ic.setGravity(
android.view.Gravity.CENTER
);

item.addView(
ic,
new android.widget.LinearLayout.LayoutParams(
65,
70
)
);

android.widget.TextView tx =
new android.widget.TextView(
MainActivity.this
);

tx.setText(
"رجوع للمجلد"
);

tx.setTextColor(WHITE);
tx.setTextSize(16);
tx.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.addView(
tx,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

item.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        load(side);      
    }      
}

);

}

void addSearchItem(
final int side,
final java.io.File f
) {

android.widget.LinearLayout item =
new android.widget.LinearLayout(
MainActivity.this
);

item.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.setPadding(
7,
4,
3,
4
);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setColor(CARD);
bg.setCornerRadius(18);

item.setBackground(bg);

android.widget.LinearLayout.LayoutParams lp =
new android.widget.LinearLayout.LayoutParams(
-1,
84
);

lp.setMargins(
0,
3,
0,
3
);

LIST[side].addView(
item,
lp
);

android.widget.TextView ic =
new android.widget.TextView(
MainActivity.this
);

ic.setText(
icon(f)
);

ic.setTextColor(
f.isDirectory()
? BLUE
: GRAY
);

ic.setTextSize(25);
ic.setGravity(
android.view.Gravity.CENTER
);

item.addView(
ic,
new android.widget.LinearLayout.LayoutParams(
58,
72
)
);

android.widget.LinearLayout text =
new android.widget.LinearLayout(
MainActivity.this
);

text.setOrientation(
android.widget.LinearLayout.VERTICAL
);

text.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

item.addView(
text,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

android.widget.TextView name =
new android.widget.TextView(
MainActivity.this
);

name.setText(
f.getName()
);

name.setTextColor(WHITE);
name.setTextSize(15);
name.setSingleLine(true);

name.setEllipsize(
android.text.TextUtils.TruncateAt.END
);

text.addView(name);

android.widget.TextView path =
new android.widget.TextView(
MainActivity.this
);

path.setText(
f.getParent()
);

path.setTextColor(GRAY);
path.setTextSize(9);
path.setSingleLine(true);

path.setEllipsize(
android.text.TextUtils.TruncateAt.MIDDLE
);

text.addView(path);

item.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        if (f.isDirectory()) {      

            HISTORY[side].add(      
                DIR[side]      
                .getAbsolutePath()      
            );      

            FUTURE[side].clear();      

            DIR[side] = f;      

            load(side);      

        } else {      

            openEditor(f);      
        }      
    }      
}

);

}

void showHamburgerNetworkMenu() {

if (TOP_POPUP != null && TOP_POPUP.isShowing()) {
    TOP_POPUP.dismiss();
    return;
}

final android.widget.LinearLayout menu =
new android.widget.LinearLayout(MainActivity.this);

menu.setOrientation(
android.widget.LinearLayout.VERTICAL
);

menu.setPadding(
12 * DP,
12 * DP,
12 * DP,
12 * DP
);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setColor(
android.graphics.Color.rgb(30, 34, 41)
);
bg.setCornerRadius(20 * DP);

menu.setBackground(bg);
menu.setElevation(24);

TOP_POPUP =
new android.widget.PopupWindow(
menu,
270 * DP,
-2,
true
);

TOP_POPUP.setBackgroundDrawable(
new android.graphics.drawable.ColorDrawable(
android.graphics.Color.TRANSPARENT
)
);

TOP_POPUP.setOutsideTouchable(true);

android.widget.TextView title =
new android.widget.TextView(MainActivity.this);

title.setText("اتصالات FTP / SFTP / FTPS");
title.setTextColor(WHITE);
title.setTextSize(17);
title.setTypeface(
android.graphics.Typeface.DEFAULT,
android.graphics.Typeface.BOLD
);
title.setGravity(android.view.Gravity.CENTER_VERTICAL);
title.setPadding(
14 * DP,
4 * DP,
14 * DP,
12 * DP
);

menu.addView(
    title,
    new android.widget.LinearLayout.LayoutParams(
        -1,
        50 * DP
    )
);

if (savedHosts.size() == 0) {

    android.widget.TextView empty =
    new android.widget.TextView(MainActivity.this);

    empty.setText(
        "لم تضف أي اتصال\nFTP • SFTP • FTPS"
    );

    empty.setTextColor(GRAY);
    empty.setTextSize(14);
    empty.setGravity(android.view.Gravity.CENTER);
    empty.setPadding(
        8 * DP,
        8 * DP,
        8 * DP,
        8 * DP
    );

    android.graphics.drawable.GradientDrawable emptyBg =
    new android.graphics.drawable.GradientDrawable();

    emptyBg.setColor(
        android.graphics.Color.rgb(38, 43, 52)
    );
    emptyBg.setCornerRadius(14 * DP);
    empty.setBackground(emptyBg);

    android.widget.LinearLayout.LayoutParams emptyLp =
    new android.widget.LinearLayout.LayoutParams(
        -1,
        82 * DP
    );

    emptyLp.setMargins(
        0,
        2 * DP,
        0,
        8 * DP
    );

    menu.addView(empty, emptyLp);

} else {

    for (int i = 0; i < savedHosts.size(); i++) {

        final int index = i;

        android.widget.LinearLayout row =
        new android.widget.LinearLayout(MainActivity.this);

        row.setOrientation(
            android.widget.LinearLayout.HORIZONTAL
        );

        row.setGravity(
            android.view.Gravity.CENTER_VERTICAL
        );

        row.setPadding(
            8 * DP,
            4 * DP,
            8 * DP,
            4 * DP
        );

        android.graphics.drawable.GradientDrawable rowBg =
        new android.graphics.drawable.GradientDrawable();

        rowBg.setColor(
            android.graphics.Color.rgb(40, 45, 55)
        );
        rowBg.setCornerRadius(14 * DP);
        row.setBackground(rowBg);

        android.widget.TextView typeView =
        new android.widget.TextView(MainActivity.this);

        String typeText =
        savedTypes.get(i) == 0
        ? "FTP"
        : savedTypes.get(i) == 1
        ? "SFTP"
        : "FTPS";

        typeView.setText(typeText);
        typeView.setTextColor(BLUE);
        typeView.setTextSize(12);
        typeView.setTypeface(
            android.graphics.Typeface.DEFAULT,
            android.graphics.Typeface.BOLD
        );
        typeView.setGravity(android.view.Gravity.CENTER);

        row.addView(
            typeView,
            new android.widget.LinearLayout.LayoutParams(
                62 * DP,
                52 * DP
            )
        );

        android.widget.LinearLayout textBox =
        new android.widget.LinearLayout(MainActivity.this);

        textBox.setOrientation(
            android.widget.LinearLayout.VERTICAL
        );
        textBox.setGravity(
            android.view.Gravity.CENTER_VERTICAL
        );

        row.addView(
            textBox,
            new android.widget.LinearLayout.LayoutParams(
                0,
                -1,
                1
            )
        );

        android.widget.TextView host =
        new android.widget.TextView(MainActivity.this);

        host.setText(savedHosts.get(i));
        host.setTextColor(WHITE);
        host.setTextSize(14);
        host.setSingleLine(true);
        host.setEllipsize(
            android.text.TextUtils.TruncateAt.END
        );

        textBox.addView(host);

        String remark =
        savedRemarks.size() > i
        ? savedRemarks.get(i)
        : "";

        if (remark.length() > 0) {

            android.widget.TextView sub =
            new android.widget.TextView(MainActivity.this);

            sub.setText(remark);
            sub.setTextColor(GRAY);
            sub.setTextSize(10);
            sub.setSingleLine(true);
            sub.setEllipsize(
                android.text.TextUtils.TruncateAt.END
            );

            textBox.addView(sub);
        }

        android.widget.TextView arrow =
        new android.widget.TextView(MainActivity.this);

        arrow.setText("›");
        arrow.setTextColor(GRAY);
        arrow.setTextSize(24);
        arrow.setGravity(android.view.Gravity.CENTER);

        row.addView(
            arrow,
            new android.widget.LinearLayout.LayoutParams(
                34 * DP,
                52 * DP
            )
        );

        android.widget.LinearLayout.LayoutParams rowLp =
        new android.widget.LinearLayout.LayoutParams(
            -1,
            58 * DP
        );

        rowLp.setMargins(
            0,
            3 * DP,
            0,
            3 * DP
        );

        menu.addView(row, rowLp);

        row.setOnClickListener(
        new android.view.View.OnClickListener() {
            @Override
            public void onClick(android.view.View v) {

                if (TOP_POPUP != null)
                    TOP_POPUP.dismiss();

                connectFTP(index);
            }
        }
        );
    }
}

android.widget.TextView divider =
new android.widget.TextView(MainActivity.this);

divider.setText("──────────────");
divider.setTextColor(
    android.graphics.Color.rgb(70, 77, 90)
);
divider.setGravity(android.view.Gravity.CENTER);

menu.addView(
    divider,
    new android.widget.LinearLayout.LayoutParams(
        -1,
        24 * DP
    )
);

android.widget.TextView addBtn =
new android.widget.TextView(MainActivity.this);

addBtn.setText("＋  إضافة اتصال");
addBtn.setTextColor(WHITE);
addBtn.setTextSize(14);
addBtn.setTypeface(
    android.graphics.Typeface.DEFAULT,
    android.graphics.Typeface.BOLD
);
addBtn.setGravity(android.view.Gravity.CENTER);

android.graphics.drawable.GradientDrawable addBg =
new android.graphics.drawable.GradientDrawable();

addBg.setColor(BLUE);
addBg.setCornerRadius(14 * DP);
addBtn.setBackground(addBg);

menu.addView(
    addBtn,
    new android.widget.LinearLayout.LayoutParams(
        -1,
        52 * DP
    )
);

addBtn.setOnClickListener(
new android.view.View.OnClickListener() {
    @Override
    public void onClick(android.view.View v) {

        if (TOP_POPUP != null)
            TOP_POPUP.dismiss();

        showNetworkStorageMenu();
    }
}
);

TOP_POPUP.setAnimationStyle(
    android.R.style.Animation_Dialog
);

TOP_POPUP.showAtLocation(
    ROOT,
    android.view.Gravity.TOP |
    android.view.Gravity.LEFT,
    8 * DP,
    58 * DP
);

menu.setScaleX(0.92f);
menu.setScaleY(0.92f);
menu.setAlpha(0.0f);

menu.animate()
.scaleX(1.0f)
.scaleY(1.0f)
.alpha(1.0f)
.setDuration(150)
.start();
}

void showTopMenu() {
if (TOP_POPUP != null && TOP_POPUP.isShowing()) {
TOP_POPUP.dismiss();
return;
}

final android.widget.LinearLayout menu =
new android.widget.LinearLayout(MainActivity.this);

menu.setOrientation(
android.widget.LinearLayout.VERTICAL
);

menu.setPadding(
16 * DP,
16 * DP,
16 * DP,
16 * DP
);

android.graphics.drawable.GradientDrawable mbg =
new android.graphics.drawable.GradientDrawable();

mbg.setColor(
android.graphics.Color.rgb(30, 34, 41)
);
mbg.setCornerRadius(20 * DP);

menu.setBackground(mbg);
menu.setElevation(20);

TOP_POPUP =
new android.widget.PopupWindow(
menu,
220 * DP,
-2,
true
);

TOP_POPUP.setBackgroundDrawable(
new android.graphics.drawable.ColorDrawable(
android.graphics.Color.TRANSPARENT
)
);

TOP_POPUP.setOutsideTouchable(true);

final String[] fixedItems = {
"اللوحة اليسرى",
"اللوحة اليمنى",
"بحث",
"تحديث"
};

for (int i = 0; i < fixedItems.length; i++) {

final int action = i;

android.widget.TextView b =
new android.widget.TextView(MainActivity.this);

b.setText(fixedItems[i]);
b.setTextColor(WHITE);
b.setTextSize(15);
b.setGravity(
android.view.Gravity.CENTER_VERTICAL
);
b.setPadding(
20 * DP,
12 * DP,
20 * DP,
12 * DP
);

android.graphics.drawable.GradientDrawable bb =
new android.graphics.drawable.GradientDrawable();

bb.setColor(CARD);
bb.setCornerRadius(14 * DP);
b.setBackground(bb);

android.widget.LinearLayout.LayoutParams bp =
new android.widget.LinearLayout.LayoutParams(
-1,
50 * DP
);

bp.setMargins(
0,
4 * DP,
0,
4 * DP
);

menu.addView(b, bp);

b.setOnClickListener(
new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {

if (TOP_POPUP != null)
TOP_POPUP.dismiss();

if (action == 0) {
activate(0);
} else if (action == 1) {
activate(1);
} else if (action == 2) {
searchDialog(ACTIVE[0] ? 0 : 1);
} else {
load(0);
load(1);
}

}
}
);
}

TOP_POPUP.setAnimationStyle(
android.R.style.Animation_Dialog
);

TOP_POPUP.showAsDropDown(
MORE,
-150,
-10
);

menu.setScaleX(0.05f);
menu.setScaleY(0.05f);
menu.setAlpha(0.0f);

menu.animate()
.scaleX(1.0f)
.scaleY(1.0f)
.alpha(1.0f)
.setDuration(170)
.start();

}

void showNetworkStorageMenu() {

final android.widget.PopupWindow subPopup;

final android.widget.LinearLayout subMenu =
new android.widget.LinearLayout(MainActivity.this);

subMenu.setOrientation(
android.widget.LinearLayout.VERTICAL
);

subMenu.setPadding(
12 * DP,
12 * DP,
12 * DP,
12 * DP
);

android.graphics.drawable.GradientDrawable mbg =
new android.graphics.drawable.GradientDrawable();

mbg.setColor(android.graphics.Color.rgb(30, 34, 41));
mbg.setCornerRadius(18 * DP);

subMenu.setBackground(mbg);
subMenu.setElevation(24);

subPopup =
new android.widget.PopupWindow(
subMenu,
210 * DP,
-2,
true
);

subPopup.setBackgroundDrawable(
new android.graphics.drawable.ColorDrawable(
android.graphics.Color.TRANSPARENT
)
);

subPopup.setOutsideTouchable(true);

android.widget.TextView title =
new android.widget.TextView(MainActivity.this);

title.setText("اختر نوع الاتصال");
title.setTextColor(WHITE);
title.setTextSize(16);
title.setTypeface(
android.graphics.Typeface.DEFAULT,
android.graphics.Typeface.BOLD
);
title.setGravity(android.view.Gravity.CENTER);
title.setPadding(0, 4 * DP, 0, 10 * DP);

subMenu.addView(
title,
new android.widget.LinearLayout.LayoutParams(
-1,
42 * DP
)
);

String[] items = {
"FTP",
"SFTP",
"FTPS"
};

for (int i = 0; i < items.length; i++) {

final int type = i;

android.widget.TextView b =
new android.widget.TextView(MainActivity.this);

b.setText(items[i]);
b.setTextColor(WHITE);
b.setTextSize(15);
b.setGravity(android.view.Gravity.CENTER);

android.graphics.drawable.GradientDrawable bb =
new android.graphics.drawable.GradientDrawable();

bb.setColor(CARD);
bb.setCornerRadius(14 * DP);

b.setBackground(bb);

android.widget.LinearLayout.LayoutParams bp =
new android.widget.LinearLayout.LayoutParams(
-1,
50 * DP
);

bp.setMargins(0, 3 * DP, 0, 3 * DP);

subMenu.addView(b, bp);

b.setOnClickListener(
new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {

subPopup.dismiss();

showFTPDialog(type);
}
}
);
}

subPopup.setAnimationStyle(
android.R.style.Animation_Dialog
);

subPopup.showAtLocation(
ROOT,
android.view.Gravity.TOP |
android.view.Gravity.RIGHT,
18 * DP,
58 * DP
);

subMenu.setScaleX(0.92f);
subMenu.setScaleY(0.92f);
subMenu.setAlpha(0.0f);

subMenu.animate()
.scaleX(1.0f)
.scaleY(1.0f)
.alpha(1.0f)
.setDuration(150)
.start();
}

void openFile(java.io.File f){
    if(f==null||!f.exists()){android.widget.Toast.makeText(MainActivity.this,"الملف غير موجود",android.widget.Toast.LENGTH_SHORT).show();return;}
    try{android.os.StrictMode.setVmPolicy(new android.os.StrictMode.VmPolicy.Builder().build());android.content.Intent i=new android.content.Intent(android.content.Intent.ACTION_VIEW);i.setDataAndType(android.net.Uri.fromFile(f),"*/*");i.addFlags(android.content.Intent.FLAG_ACTIVITY_NEW_TASK);MainActivity.this.startActivity(i);}catch(Exception e){android.widget.Toast.makeText(MainActivity.this,"لا يوجد تطبيق مناسب لفتح الملف",android.widget.Toast.LENGTH_SHORT).show();}
}

void openRemoteExternal(final int side,final String displayName){
    if(!REMOTE_MODE[side]||REMOTE_INDEX[side]<0)return;final int index=REMOTE_INDEX[side];final String base=(REMOTE_PATH[side]==null||REMOTE_PATH[side].length()==0)?"/":REMOTE_PATH[side];final String remoteFile=base.equals("/")?"/"+displayName:(base.endsWith("/")?base+displayName:base+"/"+displayName);
    new Thread(new Runnable(){public void run(){java.io.File tmp=null;try{String host=savedHosts.get(index);int port=savedPorts.get(index);String user=savedUsers.get(index);String pass=savedPasswords.get(index);int type=savedTypes.get(index);tmp=java.io.File.createTempFile("remote_",".tmp",MainActivity.this.getCacheDir());java.io.OutputStream out=new java.io.BufferedOutputStream(new java.io.FileOutputStream(tmp),64*1024);if(type==0){org.apache.commons.net.ftp.FTPClient ftp=new org.apache.commons.net.ftp.FTPClient();ftp.connect(host,port);if(!ftp.login(user,pass))throw new Exception("FTP Login failed");ftp.enterLocalPassiveMode();if(!ftp.retrieveFile(remoteFile,out))throw new Exception("FTP download failed");ftp.logout();ftp.disconnect();}else if(type==1){com.jcraft.jsch.JSch j=new com.jcraft.jsch.JSch();com.jcraft.jsch.Session s=j.getSession(user,host,port);s.setPassword(pass);java.util.Properties cfg=new java.util.Properties();cfg.put("StrictHostKeyChecking","no");s.setConfig(cfg);s.connect(15000);com.jcraft.jsch.ChannelSftp ch=(com.jcraft.jsch.ChannelSftp)s.openChannel("sftp");ch.connect(10000);ch.get(remoteFile,out);ch.disconnect();s.disconnect();}else{org.apache.commons.net.ftp.FTPSClient ftp=new org.apache.commons.net.ftp.FTPSClient();ftp.setConnectTimeout(15000);ftp.connect(host,port);if(!ftp.login(user,pass))throw new Exception("FTPS Login failed");ftp.execPBSZ(0);ftp.execPROT("P");ftp.enterLocalPassiveMode();if(!ftp.retrieveFile(remoteFile,out))throw new Exception("FTPS download failed");ftp.logout();ftp.disconnect();}out.close();final java.io.File f=tmp;MainActivity.this.runOnUiThread(new Runnable(){public void run(){try{android.os.StrictMode.setVmPolicy(new android.os.StrictMode.VmPolicy.Builder().build());android.content.Intent i=new android.content.Intent(android.content.Intent.ACTION_VIEW);i.setDataAndType(android.net.Uri.fromFile(f),"*/*");i.addFlags(android.content.Intent.FLAG_ACTIVITY_NEW_TASK);MainActivity.this.startActivity(i);}catch(Exception e){android.widget.Toast.makeText(MainActivity.this,"لا يوجد تطبيق لفتح الملف",android.widget.Toast.LENGTH_SHORT).show();}}});}catch(final Exception e){if(tmp!=null)try{tmp.delete();}catch(Exception ignored){}MainActivity.this.runOnUiThread(new Runnable(){public void run(){android.widget.Toast.makeText(MainActivity.this,"فشل فتح الملف: "+e.getMessage(),android.widget.Toast.LENGTH_LONG).show();}});}}}).start();
}

void openEditor(java.io.File file) {

if (file == null || !file.exists()) {

android.widget.Toast.makeText(
MainActivity.this,
"الملف غير موجود",
android.widget.Toast.LENGTH_SHORT
).show();

return;

}

try {

android.content.Intent intent =
new android.content.Intent(
MainActivity.this,
EditorActivity.class
);

intent.putExtra("file_path", file.getAbsolutePath());

MainActivity.this.startActivity(intent);

} catch (Exception e) {

android.widget.Toast.makeText(
MainActivity.this,
"فشل فتح المحرر",
android.widget.Toast.LENGTH_SHORT
).show();

}

}

void showActions(
final int side,
final java.io.File file
) {

final android.app.Dialog dialog = new android.app.Dialog(MainActivity.this);
dialog.requestWindowFeature(android.view.Window.FEATURE_NO_TITLE);

android.widget.LinearLayout panel = new android.widget.LinearLayout(MainActivity.this);
panel.setOrientation(android.widget.LinearLayout.VERTICAL);
panel.setPadding(16 * DP, 16 * DP, 16 * DP, 20 * DP);

android.graphics.drawable.GradientDrawable panelBg = new android.graphics.drawable.GradientDrawable();
panelBg.setColor(android.graphics.Color.rgb(35, 39, 46));
panelBg.setCornerRadius(20 * DP);
panel.setBackground(panelBg);

android.widget.LinearLayout header = new android.widget.LinearLayout(MainActivity.this);
header.setOrientation(android.widget.LinearLayout.HORIZONTAL);
header.setGravity(android.view.Gravity.CENTER_VERTICAL);
header.setPadding(0, 0, 0, 12 * DP);

android.widget.TextView title = new android.widget.TextView(MainActivity.this);
title.setText(file.getName());
title.setTextColor(WHITE);
title.setTextSize(16);
title.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);
title.setGravity(android.view.Gravity.CENTER_VERTICAL);

android.widget.LinearLayout.LayoutParams titleParams = new android.widget.LinearLayout.LayoutParams(0, -2, 1);
header.addView(title, titleParams);

android.widget.TextView closeBtn = new android.widget.TextView(MainActivity.this);
closeBtn.setText("✕");
closeBtn.setTextColor(GRAY);
closeBtn.setTextSize(22);
closeBtn.setGravity(android.view.Gravity.CENTER);
closeBtn.setPadding(10 * DP, 0, 0, 0);

closeBtn.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
dialog.dismiss();
}
});

header.addView(closeBtn, new android.widget.LinearLayout.LayoutParams(44 * DP, 44 * DP));

panel.addView(header);

android.widget.GridLayout grid = new android.widget.GridLayout(MainActivity.this);
grid.setColumnCount(2);
grid.setRowCount(5);

String[] names = {"Copy →", "Move →", "Delete", "Rename", "Tools", "Compress", "Properties", "Share", "Open with", "Bookmark"};

for (int x = 0; x < names.length; x++) {
final int ACTION = x;
android.widget.LinearLayout b = new android.widget.LinearLayout(MainActivity.this);
b.setOrientation(android.widget.LinearLayout.VERTICAL);
b.setGravity(android.view.Gravity.CENTER);
b.setPadding(8 * DP, 8 * DP, 8 * DP, 8 * DP);

android.graphics.drawable.GradientDrawable bbg = new android.graphics.drawable.GradientDrawable();  
bbg.setColor(CARD);  
bbg.setCornerRadius(14 * DP);  
b.setBackground(bbg);  

android.widget.TextView bi = new android.widget.TextView(MainActivity.this);  
String[] icons = {"📋", "✂", "🗑", "✏", "🔧", "🗜", "ℹ", "📤", "📂", "🔖"};  
bi.setText(icons[x]);  
bi.setTextSize(24);  
bi.setGravity(android.view.Gravity.CENTER);  

b.addView(bi, new android.widget.LinearLayout.LayoutParams(56 * DP, 50 * DP));  

android.widget.TextView bt = new android.widget.TextView(MainActivity.this);  
bt.setText(names[x]);  
bt.setTextColor(WHITE);  
bt.setTextSize(11);  
bt.setGravity(android.view.Gravity.CENTER);  

b.addView(bt, new android.widget.LinearLayout.LayoutParams(-2, 28 * DP));  

android.widget.GridLayout.LayoutParams gp = new android.widget.GridLayout.LayoutParams();  
gp.width = 0;  
gp.height = 90 * DP;  
gp.columnSpec = android.widget.GridLayout.spec(x % 2, 1, 1);  
gp.rowSpec = android.widget.GridLayout.spec(x / 2);  
gp.setMargins(4 * DP, 4 * DP, 4 * DP, 4 * DP);  
grid.addView(b, gp);  

b.setOnClickListener(new android.view.View.OnClickListener() {  
    @Override  
    public void onClick(android.view.View v) {  
        dialog.dismiss();  
        if (ACTION == 0) {  
            copyFile(side, file);  
        } else if (ACTION == 1) {  
            moveFile(side, file);  
        } else if (ACTION == 2) {  
            deleteFile(side, file);  
        } else if (ACTION == 3) {  
            renameFile(side, file);  
        } else if (ACTION == 4) {  
            android.widget.Toast.makeText(MainActivity.this, "Tools", android.widget.Toast.LENGTH_SHORT).show();  
        } else if (ACTION == 5) {  
            compressFile(side, file);  
        } else if (ACTION == 6) {  
            properties(file);  
        } else if (ACTION == 7) {  
            shareFile(file);  
        } else if (ACTION == 8) {  
            openEditor(file);  
        } else {  
            android.widget.Toast.makeText(MainActivity.this, "تمت إضافة العلامة", android.widget.Toast.LENGTH_SHORT).show();  
        }  
    }  
});

}

panel.addView(grid, new android.widget.LinearLayout.LayoutParams(-1, -2));

dialog.setContentView(panel);

android.view.Window window = dialog.getWindow();
if (window != null) {
window.setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));
window.setDimAmount(0.65f);
window.addFlags(android.view.WindowManager.LayoutParams.FLAG_DIM_BEHIND);
window.setLayout((int)(310 * DP), android.view.WindowManager.LayoutParams.WRAP_CONTENT);
window.setGravity(android.view.Gravity.CENTER);
}

dialog.show();

}

void showProgressDialog(String title, String message) {
if (progressDialog != null && progressDialog.isShowing()) {
progressDialog.dismiss();
}

progressDialog = new android.app.Dialog(MainActivity.this);  
progressDialog.requestWindowFeature(android.view.Window.FEATURE_NO_TITLE);  

android.widget.LinearLayout root = new android.widget.LinearLayout(MainActivity.this);  
root.setOrientation(android.widget.LinearLayout.VERTICAL);  
root.setPadding(20 * DP, 20 * DP, 20 * DP, 20 * DP);  

android.graphics.drawable.GradientDrawable bg = new android.graphics.drawable.GradientDrawable();  
bg.setColor(android.graphics.Color.rgb(35, 39, 46));  
bg.setCornerRadius(20 * DP);  
root.setBackground(bg);  

android.widget.LinearLayout header = new android.widget.LinearLayout(MainActivity.this);  
header.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
header.setGravity(android.view.Gravity.CENTER_VERTICAL);  

android.widget.TextView titleView = new android.widget.TextView(MainActivity.this);  
titleView.setText(title);  
titleView.setTextColor(WHITE);  
titleView.setTextSize(16);  
titleView.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);  

android.widget.LinearLayout.LayoutParams titleParams = new android.widget.LinearLayout.LayoutParams(0, -2, 1);  
header.addView(titleView, titleParams);  

android.widget.TextView closeBtn = new android.widget.TextView(MainActivity.this);  
closeBtn.setText("✕");  
closeBtn.setTextColor(GRAY);  
closeBtn.setTextSize(22);  
closeBtn.setGravity(android.view.Gravity.CENTER);  
closeBtn.setPadding(10 * DP, 0, 0, 0);  

closeBtn.setOnClickListener(new android.view.View.OnClickListener() {  
    @Override  
    public void onClick(android.view.View v) {  
        if (progressDialog != null && progressDialog.isShowing()) {  
            progressDialog.dismiss();  
            progressDialog = null;  
        }  
    }  
});  

header.addView(closeBtn, new android.widget.LinearLayout.LayoutParams(44 * DP, 44 * DP));  
root.addView(header, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.widget.Space space = new android.widget.Space(MainActivity.this);  
root.addView(space, new android.widget.LinearLayout.LayoutParams(1, 16 * DP));  

progressText = new android.widget.TextView(MainActivity.this);  
progressText.setText(message);  
progressText.setTextColor(GRAY);  
progressText.setTextSize(14);  
progressText.setGravity(android.view.Gravity.CENTER);  
root.addView(progressText, new android.widget.LinearLayout.LayoutParams(-1, -2));  

android.widget.Space space2 = new android.widget.Space(MainActivity.this);  
root.addView(space2, new android.widget.LinearLayout.LayoutParams(1, 16 * DP));  

progressBar = new android.widget.ProgressBar(MainActivity.this, null, android.R.attr.progressBarStyleHorizontal);  
progressBar.setIndeterminate(true);  
android.graphics.drawable.GradientDrawable progressBg = new android.graphics.drawable.GradientDrawable();  
progressBg.setColor(android.graphics.Color.rgb(45, 50, 58));  
progressBg.setCornerRadius(10 * DP);  
progressBar.setProgressDrawable(progressBg);  
root.addView(progressBar, new android.widget.LinearLayout.LayoutParams(-1, 8 * DP));  

android.widget.Space space3 = new android.widget.Space(MainActivity.this);  
root.addView(space3, new android.widget.LinearLayout.LayoutParams(1, 10 * DP));  

android.widget.LinearLayout btnRow = new android.widget.LinearLayout(MainActivity.this);  
btnRow.setOrientation(android.widget.LinearLayout.HORIZONTAL);  
btnRow.setGravity(android.view.Gravity.CENTER);  

android.widget.TextView hideBtn = new android.widget.TextView(MainActivity.this);  
hideBtn.setText("—");  
hideBtn.setTextColor(WHITE);  
hideBtn.setTextSize(24);  
hideBtn.setGravity(android.view.Gravity.CENTER);  
hideBtn.setPadding(20 * DP, 8 * DP, 20 * DP, 8 * DP);  

android.graphics.drawable.GradientDrawable hideBg = new android.graphics.drawable.GradientDrawable();  
hideBg.setColor(CARD);  
hideBg.setCornerRadius(12 * DP);  
hideBtn.setBackground(hideBg);  

hideBtn.setOnClickListener(new android.view.View.OnClickListener() {  
    @Override  
    public void onClick(android.view.View v) {  
        progressHidden = !progressHidden;  
        if (progressHidden) {  
            progressDialog.hide();  
        } else {  
            progressDialog.show();  
        }  
    }  
});  

btnRow.addView(hideBtn, new android.widget.LinearLayout.LayoutParams(80 * DP, 44 * DP));  

android.widget.Space btnSpace = new android.widget.Space(MainActivity.this);  
btnRow.addView(btnSpace, new android.widget.LinearLayout.LayoutParams(16 * DP, 1));  

android.widget.TextView cancelBtn = new android.widget.TextView(MainActivity.this);  
cancelBtn.setText("إلغاء");  
cancelBtn.setTextColor(WHITE);  
cancelBtn.setTextSize(14);  
cancelBtn.setGravity(android.view.Gravity.CENTER);  
cancelBtn.setPadding(20 * DP, 8 * DP, 20 * DP, 8 * DP);  

android.graphics.drawable.GradientDrawable cancelBg = new android.graphics.drawable.GradientDrawable();  
cancelBg.setColor(android.graphics.Color.rgb(200, 60, 60));  
cancelBg.setCornerRadius(12 * DP);  
cancelBtn.setBackground(cancelBg);  

cancelBtn.setOnClickListener(new android.view.View.OnClickListener() {  
    @Override  
    public void onClick(android.view.View v) {  
        if (progressDialog != null && progressDialog.isShowing()) {  
            progressDialog.dismiss();  
            progressDialog = null;  
            progressHidden = false;  
        }  
    }  
});  

btnRow.addView(cancelBtn, new android.widget.LinearLayout.LayoutParams(100 * DP, 44 * DP));  

root.addView(btnRow, new android.widget.LinearLayout.LayoutParams(-1, -2));  

progressDialog.setContentView(root);  

android.view.Window window = progressDialog.getWindow();  
if (window != null) {  
    window.setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));  
    window.setDimAmount(0.65f);  
    window.addFlags(android.view.WindowManager.LayoutParams.FLAG_DIM_BEHIND);  
    window.setLayout((int)(320 * DP), android.view.WindowManager.LayoutParams.WRAP_CONTENT);  
    window.setGravity(android.view.Gravity.CENTER);  
}  

progressDialog.show();

}

void copyFile(
final int side,
final java.io.File src
) {

int other =
side == 0
? 1
: 0;

java.io.File dst =
new java.io.File(
DIR[other],
src.getName()
);

showProgressDialog("نسخ الملف", "جاري نسخ " + src.getName() + "...");

try {
copyRecursive(src, dst);

if (progressDialog != null && progressDialog.isShowing()) {  
    progressDialog.dismiss();  
    progressDialog = null;  
    progressHidden = false;  
}  

load(0);      
load(1);      

android.widget.Toast.makeText(      
    MainActivity.this,      
    "تم النسخ",      
    android.widget.Toast.LENGTH_SHORT      
).show();

} catch (
Exception e
) {
if (progressDialog != null && progressDialog.isShowing()) {
progressDialog.dismiss();
progressDialog = null;
progressHidden = false;
}
android.widget.Toast.makeText(
MainActivity.this,
"فشل النسخ",
android.widget.Toast.LENGTH_SHORT
).show();
}

}

void moveFile(
final int side,
final java.io.File src
) {

int other =
side == 0
? 1
: 0;

java.io.File dst =
new java.io.File(
DIR[other],
src.getName()
);

showProgressDialog("نقل الملف", "جاري نقل " + src.getName() + "...");

try {

if (!src.renameTo(dst)) {      
    copyRecursive(src, dst);      
    deleteRecursive(src);      
}      

if (progressDialog != null && progressDialog.isShowing()) {  
    progressDialog.dismiss();  
    progressDialog = null;  
    progressHidden = false;  
}  

load(0);      
load(1);      

android.widget.Toast.makeText(      
    MainActivity.this,      
    "تم النقل",      
    android.widget.Toast.LENGTH_SHORT      
).show();

} catch (
Exception e
) {
if (progressDialog != null && progressDialog.isShowing()) {
progressDialog.dismiss();
progressDialog = null;
progressHidden = false;
}
android.widget.Toast.makeText(
MainActivity.this,
"فشل النقل",
android.widget.Toast.LENGTH_SHORT
).show();
}

}

void copyRecursive(
java.io.File src,
java.io.File dst
) throws Exception {

if (src.isDirectory()) {

if (!dst.exists())      
    dst.mkdirs();      

java.io.File[] a =      
src.listFiles();      

if (a != null) {      

    for (      
        int i = 0;      
        i < a.length;      
        i++      
    ) {      

        copyRecursive(      
            a[i],      
            new java.io.File(      
                dst,      
                a[i].getName()      
            )      
        );      
    }      
}

} else {

java.io.FileInputStream in =      
new java.io.FileInputStream(src);      

java.io.FileOutputStream out =      
new java.io.FileOutputStream(dst);      

byte[] buffer =      
new byte[8192];      

int n;      

while (      
    (n = in.read(buffer)) > 0      
) {      

    out.write(      
        buffer,      
        0,      
        n      
    );      
}      

in.close();      
out.close();

}

}

void deleteRecursive(
java.io.File f
) {

if (f.isDirectory()) {

java.io.File[] a =      
f.listFiles();      

if (a != null) {      

    for (      
        int i = 0;      
        i < a.length;      
        i++      
    ) {      

        deleteRecursive(a[i]);      
    }      
}

}

f.delete();

}

void deleteFile(
final int side,
final java.io.File file
) {

new android.app.AlertDialog.Builder(
MainActivity.this
)
.setTitle("حذف")
.setMessage(
"حذف " +
file.getName() +
" ؟"
)
.setNegativeButton(
"إلغاء",
null
)
.setPositiveButton(
"حذف",
new android.content.DialogInterface.OnClickListener() {

@Override      
    public void onClick(      
        android.content.DialogInterface d,      
        int w      
    ) {      

        deleteRecursive(file);      

        load(0);      
        load(1);      
    }      
}

)
.show();

}

void renameFile(
final int side,
final java.io.File file
) {

final android.app.Dialog dialog = new android.app.Dialog(MainActivity.this);
dialog.requestWindowFeature(android.view.Window.FEATURE_NO_TITLE);

android.widget.LinearLayout root = new android.widget.LinearLayout(MainActivity.this);
root.setOrientation(android.widget.LinearLayout.VERTICAL);
root.setPadding(20 * DP, 20 * DP, 20 * DP, 20 * DP);

android.graphics.drawable.GradientDrawable rootBg = new android.graphics.drawable.GradientDrawable();
rootBg.setColor(android.graphics.Color.rgb(35, 39, 46));
rootBg.setCornerRadius(20 * DP);
root.setBackground(rootBg);

android.widget.LinearLayout header = new android.widget.LinearLayout(MainActivity.this);
header.setOrientation(android.widget.LinearLayout.HORIZONTAL);
header.setGravity(android.view.Gravity.CENTER_VERTICAL);

android.widget.TextView title = new android.widget.TextView(MainActivity.this);
title.setText("إعادة تسمية");
title.setTextColor(WHITE);
title.setTextSize(18);
title.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);

android.widget.LinearLayout.LayoutParams titleParams = new android.widget.LinearLayout.LayoutParams(0, -2, 1);
header.addView(title, titleParams);

android.widget.TextView closeBtn = new android.widget.TextView(MainActivity.this);
closeBtn.setText("✕");
closeBtn.setTextColor(GRAY);
closeBtn.setTextSize(22);
closeBtn.setGravity(android.view.Gravity.CENTER);
closeBtn.setPadding(10 * DP, 0, 0, 0);

closeBtn.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
dialog.dismiss();
}
});

header.addView(closeBtn, new android.widget.LinearLayout.LayoutParams(44 * DP, 44 * DP));
root.addView(header, new android.widget.LinearLayout.LayoutParams(-1, -2));

android.widget.Space space = new android.widget.Space(MainActivity.this);
root.addView(space, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));

final android.widget.EditText input = new android.widget.EditText(MainActivity.this);
input.setText(file.getName());
input.setSingleLine(true);
input.setTextColor(WHITE);
input.setHintTextColor(GRAY);
input.setTextSize(15);
input.setPadding(16 * DP, 12 * DP, 16 * DP, 12 * DP);

android.graphics.drawable.GradientDrawable inputBg = new android.graphics.drawable.GradientDrawable();
inputBg.setColor(android.graphics.Color.rgb(20, 24, 30));
inputBg.setCornerRadius(14 * DP);
inputBg.setStroke(1 * DP, android.graphics.Color.rgb(55, 63, 75));
input.setBackground(inputBg);

root.addView(input, new android.widget.LinearLayout.LayoutParams(-1, 52 * DP));

android.widget.Space space2 = new android.widget.Space(MainActivity.this);
root.addView(space2, new android.widget.LinearLayout.LayoutParams(1, 16 * DP));

android.widget.LinearLayout buttons = new android.widget.LinearLayout(MainActivity.this);
buttons.setOrientation(android.widget.LinearLayout.HORIZONTAL);
buttons.setGravity(android.view.Gravity.CENTER);

android.widget.TextView cancel = new android.widget.TextView(MainActivity.this);
cancel.setText("إلغاء");
cancel.setTextColor(GRAY);
cancel.setTextSize(14);
cancel.setGravity(android.view.Gravity.CENTER);
cancel.setPadding(20 * DP, 10 * DP, 20 * DP, 10 * DP);

android.graphics.drawable.GradientDrawable cancelBg = new android.graphics.drawable.GradientDrawable();
cancelBg.setColor(android.graphics.Color.rgb(45, 50, 58));
cancelBg.setCornerRadius(14 * DP);
cancel.setBackground(cancelBg);

buttons.addView(cancel, new android.widget.LinearLayout.LayoutParams(0, 46 * DP, 1));

android.widget.Space btnSpace = new android.widget.Space(MainActivity.this);
buttons.addView(btnSpace, new android.widget.LinearLayout.LayoutParams(12 * DP, 1));

android.widget.TextView save = new android.widget.TextView(MainActivity.this);
save.setText("حفظ");
save.setTextColor(WHITE);
save.setTextSize(14);
save.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);
save.setGravity(android.view.Gravity.CENTER);
save.setPadding(20 * DP, 10 * DP, 20 * DP, 10 * DP);

android.graphics.drawable.GradientDrawable saveBg = new android.graphics.drawable.GradientDrawable();
saveBg.setColor(BLUE);
saveBg.setCornerRadius(14 * DP);
save.setBackground(saveBg);

buttons.addView(save, new android.widget.LinearLayout.LayoutParams(0, 46 * DP, 1));

root.addView(buttons, new android.widget.LinearLayout.LayoutParams(-1, -2));

cancel.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
dialog.dismiss();
}
});

save.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
String n = input.getText().toString().trim();
if (n.length() == 0) return;
file.renameTo(new java.io.File(file.getParent(), n));
load(0);
load(1);
dialog.dismiss();
}
});

dialog.setContentView(root);

android.view.Window window = dialog.getWindow();
if (window != null) {
window.setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));
window.setDimAmount(0.65f);
window.addFlags(android.view.WindowManager.LayoutParams.FLAG_DIM_BEHIND);
window.setLayout((int)(320 * DP), android.view.WindowManager.LayoutParams.WRAP_CONTENT);
window.setGravity(android.view.Gravity.CENTER);
}

dialog.setOnShowListener(new android.content.DialogInterface.OnShowListener() {
@Override
public void onShow(android.content.DialogInterface d) {
input.requestFocus();
input.setSelection(input.getText().length());
android.view.Window w = dialog.getWindow();
if (w != null) {
w.setSoftInputMode(android.view.WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_VISIBLE);
}
}
});

dialog.show();

}

void properties(
java.io.File file
) {

String text =
"Name:\n" +
file.getName() +
"\n\nPath:\n" +
file.getAbsolutePath() +
"\n\nType:\n" +
(
file.isDirectory()
? "Folder"
: "File"
) +
"\n\nSize:\n" +
size(file.length());

new android.app.AlertDialog.Builder(
MainActivity.this
)
.setTitle("Properties")
.setMessage(text)
.setPositiveButton(
"OK",
null
)
.show();

}

void shareFile(
java.io.File file
) {

try {

android.os.StrictMode.setVmPolicy(      
    new android.os.StrictMode.VmPolicy.Builder()      
    .build()      
);      

android.content.Intent i =      
new android.content.Intent(      
    android.content.Intent.ACTION_SEND      
);      

i.setType("*/*");      

i.putExtra(      
    android.content.Intent.EXTRA_STREAM,      
    android.net.Uri.fromFile(file)      
);      

MainActivity.this.startActivity(      
    android.content.Intent.createChooser(      
        i,      
        "Share"      
    )      
);

} catch (
Exception e
) {

android.widget.Toast.makeText(      
    MainActivity.this,      
    "فشل المشاركة",      
    android.widget.Toast.LENGTH_SHORT      
).show();

}

}

void compressFile(
final int side,
final java.io.File file
) {

final android.app.Dialog dialog = new android.app.Dialog(MainActivity.this);
dialog.requestWindowFeature(android.view.Window.FEATURE_NO_TITLE);

android.widget.LinearLayout root = new android.widget.LinearLayout(MainActivity.this);
root.setOrientation(android.widget.LinearLayout.VERTICAL);
root.setPadding(20 * DP, 20 * DP, 20 * DP, 20 * DP);

android.graphics.drawable.GradientDrawable rootBg = new android.graphics.drawable.GradientDrawable();
rootBg.setColor(android.graphics.Color.rgb(35, 39, 46));
rootBg.setCornerRadius(20 * DP);
root.setBackground(rootBg);

android.widget.LinearLayout header = new android.widget.LinearLayout(MainActivity.this);
header.setOrientation(android.widget.LinearLayout.HORIZONTAL);
header.setGravity(android.view.Gravity.CENTER_VERTICAL);

android.widget.TextView title = new android.widget.TextView(MainActivity.this);
title.setText("Create archive");
title.setTextColor(WHITE);
title.setTextSize(18);
title.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);

android.widget.LinearLayout.LayoutParams titleParams = new android.widget.LinearLayout.LayoutParams(0, -2, 1);
header.addView(title, titleParams);

android.widget.TextView closeBtn = new android.widget.TextView(MainActivity.this);
closeBtn.setText("✕");
closeBtn.setTextColor(GRAY);
closeBtn.setTextSize(22);
closeBtn.setGravity(android.view.Gravity.CENTER);
closeBtn.setPadding(10 * DP, 0, 0, 0);

closeBtn.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
dialog.dismiss();
}
});

header.addView(closeBtn, new android.widget.LinearLayout.LayoutParams(44 * DP, 44 * DP));
root.addView(header, new android.widget.LinearLayout.LayoutParams(-1, -2));

android.widget.Space space = new android.widget.Space(MainActivity.this);
root.addView(space, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));

android.widget.TextView label1 = new android.widget.TextView(MainActivity.this);
label1.setText("Filename");
label1.setTextColor(GRAY);
label1.setTextSize(12);
root.addView(label1, new android.widget.LinearLayout.LayoutParams(-1, -2));

final android.widget.EditText filenameInput = new android.widget.EditText(MainActivity.this);
filenameInput.setText(file.getName() + ".zip");
filenameInput.setSingleLine(true);
filenameInput.setTextColor(WHITE);
filenameInput.setHintTextColor(GRAY);
filenameInput.setTextSize(15);
filenameInput.setPadding(16 * DP, 12 * DP, 16 * DP, 12 * DP);

android.graphics.drawable.GradientDrawable inputBg = new android.graphics.drawable.GradientDrawable();
inputBg.setColor(android.graphics.Color.rgb(20, 24, 30));
inputBg.setCornerRadius(14 * DP);
inputBg.setStroke(1 * DP, android.graphics.Color.rgb(55, 63, 75));
filenameInput.setBackground(inputBg);

root.addView(filenameInput, new android.widget.LinearLayout.LayoutParams(-1, 50 * DP));

android.widget.Space space2 = new android.widget.Space(MainActivity.this);
root.addView(space2, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));

android.widget.LinearLayout formatRow = new android.widget.LinearLayout(MainActivity.this);
formatRow.setOrientation(android.widget.LinearLayout.HORIZONTAL);
formatRow.setGravity(android.view.Gravity.CENTER_VERTICAL);

android.widget.TextView formatLabel = new android.widget.TextView(MainActivity.this);
formatLabel.setText("Format");
formatLabel.setTextColor(GRAY);
formatLabel.setTextSize(12);
formatRow.addView(formatLabel, new android.widget.LinearLayout.LayoutParams(0, -2, 1));

final android.widget.Spinner formatSpinner = new android.widget.Spinner(MainActivity.this);
String[] formats = {"zip", "7z", "tar", "gz"};
android.widget.ArrayAdapter<String> adapter = new android.widget.ArrayAdapter<String>(MainActivity.this, android.R.layout.simple_spinner_item, formats);
adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
formatSpinner.setAdapter(adapter);

android.widget.LinearLayout.LayoutParams spinnerParams = new android.widget.LinearLayout.LayoutParams(120 * DP, 40 * DP);
formatRow.addView(formatSpinner, spinnerParams);

root.addView(formatRow, new android.widget.LinearLayout.LayoutParams(-1, -2));

android.widget.Space space3 = new android.widget.Space(MainActivity.this);
root.addView(space3, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));

android.widget.LinearLayout levelRow = new android.widget.LinearLayout(MainActivity.this);
levelRow.setOrientation(android.widget.LinearLayout.HORIZONTAL);
levelRow.setGravity(android.view.Gravity.CENTER_VERTICAL);

android.widget.TextView levelLabel = new android.widget.TextView(MainActivity.this);
levelLabel.setText("Level");
levelLabel.setTextColor(GRAY);
levelLabel.setTextSize(12);
levelRow.addView(levelLabel, new android.widget.LinearLayout.LayoutParams(0, -2, 1));

final android.widget.Spinner levelSpinner = new android.widget.Spinner(MainActivity.this);
String[] levels = {"Normal", "Fast", "Maximum", "None"};
android.widget.ArrayAdapter<String> levelAdapter = new android.widget.ArrayAdapter<String>(MainActivity.this, android.R.layout.simple_spinner_item, levels);
levelAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
levelSpinner.setAdapter(levelAdapter);

android.widget.LinearLayout.LayoutParams levelParams = new android.widget.LinearLayout.LayoutParams(120 * DP, 40 * DP);
levelRow.addView(levelSpinner, levelParams);

root.addView(levelRow, new android.widget.LinearLayout.LayoutParams(-1, -2));

android.widget.Space space4 = new android.widget.Space(MainActivity.this);
root.addView(space4, new android.widget.LinearLayout.LayoutParams(1, 12 * DP));

android.widget.TextView passLabel = new android.widget.TextView(MainActivity.this);
passLabel.setText("Password (no encryption if empty)");
passLabel.setTextColor(GRAY);
passLabel.setTextSize(11);
root.addView(passLabel, new android.widget.LinearLayout.LayoutParams(-1, -2));

final android.widget.EditText passInput = new android.widget.EditText(MainActivity.this);
passInput.setHint("Password");
passInput.setSingleLine(true);
passInput.setTextColor(WHITE);
passInput.setHintTextColor(GRAY);
passInput.setTextSize(15);
passInput.setPadding(16 * DP, 12 * DP, 16 * DP, 12 * DP);
passInput.setBackground(inputBg);

android.widget.LinearLayout.LayoutParams passParams = new android.widget.LinearLayout.LayoutParams(-1, 50 * DP);
passParams.setMargins(0, 4 * DP, 0, 0);
root.addView(passInput, passParams);

android.widget.Space space5 = new android.widget.Space(MainActivity.this);
root.addView(space5, new android.widget.LinearLayout.LayoutParams(1, 16 * DP));

android.widget.LinearLayout buttons = new android.widget.LinearLayout(MainActivity.this);
buttons.setOrientation(android.widget.LinearLayout.HORIZONTAL);
buttons.setGravity(android.view.Gravity.CENTER);

android.widget.TextView cancel = new android.widget.TextView(MainActivity.this);
cancel.setText("CANCEL");
cancel.setTextColor(GRAY);
cancel.setTextSize(13);
cancel.setGravity(android.view.Gravity.CENTER);
cancel.setPadding(20 * DP, 10 * DP, 20 * DP, 10 * DP);

android.graphics.drawable.GradientDrawable cancelBg = new android.graphics.drawable.GradientDrawable();
cancelBg.setColor(android.graphics.Color.rgb(45, 50, 58));
cancelBg.setCornerRadius(14 * DP);
cancel.setBackground(cancelBg);

buttons.addView(cancel, new android.widget.LinearLayout.LayoutParams(0, 46 * DP, 1));

android.widget.Space btnSpace = new android.widget.Space(MainActivity.this);
buttons.addView(btnSpace, new android.widget.LinearLayout.LayoutParams(12 * DP, 1));

android.widget.TextView ok = new android.widget.TextView(MainActivity.this);
ok.setText("OK");
ok.setTextColor(WHITE);
ok.setTextSize(13);
ok.setTypeface(android.graphics.Typeface.DEFAULT, android.graphics.Typeface.BOLD);
ok.setGravity(android.view.Gravity.CENTER);
ok.setPadding(20 * DP, 10 * DP, 20 * DP, 10 * DP);

android.graphics.drawable.GradientDrawable okBg = new android.graphics.drawable.GradientDrawable();
okBg.setColor(BLUE);
okBg.setCornerRadius(14 * DP);
ok.setBackground(okBg);

buttons.addView(ok, new android.widget.LinearLayout.LayoutParams(0, 46 * DP, 1));

root.addView(buttons, new android.widget.LinearLayout.LayoutParams(-1, -2));

cancel.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
dialog.dismiss();
}
});

ok.setOnClickListener(new android.view.View.OnClickListener() {
@Override
public void onClick(android.view.View v) {
String name = filenameInput.getText().toString().trim();
if (name.length() == 0) return;

if (!name.toLowerCase().endsWith(".zip")) {  
        name += ".zip";  
    }  

    try {  
        java.io.File zip = new java.io.File(DIR[side], name);  
        java.io.FileOutputStream fos = new java.io.FileOutputStream(zip);  
        java.util.zip.ZipOutputStream zos = new java.util.zip.ZipOutputStream(fos);  

        int level = levelSpinner.getSelectedItemPosition();  
        if (level == 0) zos.setLevel(java.util.zip.Deflater.DEFAULT_COMPRESSION);  
        else if (level == 1) zos.setLevel(java.util.zip.Deflater.BEST_SPEED);  
        else if (level == 2) zos.setLevel(java.util.zip.Deflater.BEST_COMPRESSION);  
        else zos.setLevel(java.util.zip.Deflater.NO_COMPRESSION);  

        zipRecursive(file, file.getName(), zos);  
        zos.close();  
        fos.close();  

        load(0);  
        load(1);  

        android.widget.Toast.makeText(MainActivity.this, "تم إنشاء ZIP", android.widget.Toast.LENGTH_SHORT).show();  
        dialog.dismiss();  

    } catch (Exception e) {  
        android.widget.Toast.makeText(MainActivity.this, "فشل الضغط", android.widget.Toast.LENGTH_SHORT).show();  
    }  
}

});

dialog.setContentView(root);

android.view.Window window = dialog.getWindow();
if (window != null) {
window.setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));
window.setDimAmount(0.65f);
window.addFlags(android.view.WindowManager.LayoutParams.FLAG_DIM_BEHIND);
window.setLayout((int)(340 * DP), android.view.WindowManager.LayoutParams.WRAP_CONTENT);
window.setGravity(android.view.Gravity.CENTER);
}

dialog.show();

}

void zipRecursive(
java.io.File file,
String name,
java.util.zip.ZipOutputStream zos
) throws Exception {

if (file.isDirectory()) {

java.io.File[] a =      
file.listFiles();      

if (      
    a == null ||      
    a.length == 0      
) {      

    zos.putNextEntry(      
        new java.util.zip.ZipEntry(      
            name + "/"      
        )      
    );      

    zos.closeEntry();      

    return;      
}      

for (      
    int i = 0;      
    i < a.length;      
    i++      
) {      

    zipRecursive(      
        a[i],      
        name +      
        "/" +      
        a[i].getName(),      
        zos      
    );      
}

} else {

java.io.FileInputStream in =      
new java.io.FileInputStream(file);      

zos.putNextEntry(      
    new java.util.zip.ZipEntry(name)      
);      

byte[] buffer =      
new byte[8192];      

int n;      

while (      
    (n = in.read(buffer)) > 0      
) {      

    zos.write(      
        buffer,      
        0,      
        n      
    );      
}      

in.close();      

zos.closeEntry();

}

}

void back(
int side
) {

if (SEARCHING[side]) {

load(side);      

return;

}

if (HISTORY[side].size() == 0)
return;

FUTURE[side].add(
DIR[side]
.getAbsolutePath()
);

String p =
HISTORY[side].remove(
HISTORY[side].size() - 1
);

DIR[side] =
new java.io.File(p);

load(side);

}

void forward(
int side
) {

if (SEARCHING[side])
return;

if (FUTURE[side].size() == 0)
return;

HISTORY[side].add(
DIR[side]
.getAbsolutePath()
);

String p =
FUTURE[side].remove(
FUTURE[side].size() - 1
);

DIR[side] =
new java.io.File(p);

load(side);

}

void parent(
int side
) {

if (SEARCHING[side]) {

load(side);      

return;

}

if (
DIR[side]
.getAbsolutePath()
.equals(
STORAGE.getAbsolutePath()
)
)
return;

if (
DIR[side].getParentFile() == null
)
return;

HISTORY[side].add(
DIR[side]
.getAbsolutePath()
);

FUTURE[side].clear();

DIR[side] =
DIR[side].getParentFile();

load(side);

}

String size(
long n
) {

if (n < 1024)
return n + " B";

if (
n <
1024L * 1024L
)
return String.format(
"%.1f KB",
n / 1024.0
);

if (
n <
1024L *
1024L *
1024L
)
return String.format(
"%.1f MB",
n / 1024.0 /
1024.0
);

return String.format(
"%.1f GB",
n / 1024.0 /
1024.0 /
1024.0
);

}

android.widget.FrameLayout CREATE_LAYER;

android.widget.TextView CREATE_FILE;
android.widget.TextView CREATE_FOLDER;
android.widget.TextView CREATE_ZIP;

void closeCreateMenu() {

if (CREATE_LAYER != null) {

ROOT.removeView(      
    CREATE_LAYER      
);      

CREATE_LAYER = null;

}

CREATE.setText("+");

CREATE_BG.setColor(
BLUE
);

CREATE.setBackground(
CREATE_BG
);

}

android.widget.TextView createCircle(
String emoji
) {

android.widget.TextView b =
new android.widget.TextView(
MainActivity.this
);

b.setText(emoji);
b.setTextSize(27);
b.setGravity(
android.view.Gravity.CENTER
);
b.setTextColor(WHITE);
b.setElevation(12);

android.graphics.drawable.GradientDrawable bg =
new android.graphics.drawable.GradientDrawable();

bg.setShape(
android.graphics.drawable.GradientDrawable.OVAL
);

bg.setColor(
android.graphics.Color.rgb(
43,
49,
60
)
);

bg.setStroke(
2,
BLUE
);

b.setBackground(bg);

return b;

}

void animateCreate(
final android.view.View v
) {

v.setScaleX(0.05f);
v.setScaleY(0.05f);
v.setAlpha(0.0f);

v.animate()
.scaleX(1.0f)
.scaleY(1.0f)
.alpha(1.0f)
.setDuration(180)
.start();

}

void showCreateMenu() {

if (CREATE_LAYER != null) {

closeCreateMenu();      

return;

}

final int side =
ACTIVE[0]
? 0
: 1;

CREATE_LAYER =
new android.widget.FrameLayout(
MainActivity.this
);

CREATE_LAYER.setBackgroundColor(
android.graphics.Color.TRANSPARENT);

ROOT.addView(
CREATE_LAYER,
new android.widget.FrameLayout.LayoutParams(
-1,
-1
)
);

CREATE_LAYER.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        closeCreateMenu();      
    }      
}

);

CREATE_FILE =
createCircle("📄");

CREATE_FOLDER =
createCircle("📁");

CREATE_ZIP =
createCircle("🗜");

CREATE_LAYER.addView(
CREATE_FILE,
new android.widget.FrameLayout.LayoutParams(
68,
68,
android.view.Gravity.BOTTOM |
android.view.Gravity.CENTER_HORIZONTAL
)
);

CREATE_LAYER.addView(
CREATE_FOLDER,
new android.widget.FrameLayout.LayoutParams(
68,
68,
android.view.Gravity.BOTTOM |
android.view.Gravity.CENTER_HORIZONTAL
)
);

CREATE_LAYER.addView(
CREATE_ZIP,
new android.widget.FrameLayout.LayoutParams(
68,
68,
android.view.Gravity.BOTTOM |
android.view.Gravity.CENTER_HORIZONTAL
)
);

android.widget.FrameLayout.LayoutParams fp =
(android.widget.FrameLayout.LayoutParams)
CREATE_FILE.getLayoutParams();

fp.bottomMargin = 305;

CREATE_FILE.setLayoutParams(fp);

android.widget.FrameLayout.LayoutParams fop =
(android.widget.FrameLayout.LayoutParams)
CREATE_FOLDER.getLayoutParams();

fop.bottomMargin = 175;
fop.leftMargin = -85;
fop.rightMargin = 0;

CREATE_FOLDER.setLayoutParams(fop);

android.widget.FrameLayout.LayoutParams zp =
(android.widget.FrameLayout.LayoutParams)
CREATE_ZIP.getLayoutParams();

zp.bottomMargin = 175;
zp.leftMargin = 0;
zp.rightMargin = -85;

CREATE_ZIP.setLayoutParams(zp);

animateCreate(CREATE_FILE);
animateCreate(CREATE_FOLDER);
animateCreate(CREATE_ZIP);

CREATE.setText("×");

CREATE_BG.setColor(
android.graphics.Color.rgb(
75,
85,
100
)
);

CREATE.setBackground(CREATE_BG);

CREATE_FILE.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        closeCreateMenu();      

        createInput(      
            side,      
            0      
        );      
    }      
}

);

CREATE_FOLDER.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        closeCreateMenu();      

        createInput(      
            side,      
            1      
        );      
    }      
}

);

CREATE_ZIP.setOnClickListener(
new android.view.View.OnClickListener() {

@Override      
    public void onClick(      
        android.view.View v      
    ) {      

        closeCreateMenu();      

        createInput(      
            side,      
            2      
        );      
    }      
}

);

}

void createInput(
final int side,
final int type
) {

final android.app.Dialog dialog =
new android.app.Dialog(
MainActivity.this
);

dialog.getWindow();

android.widget.LinearLayout root =
new android.widget.LinearLayout(
MainActivity.this
);

root.setOrientation(
android.widget.LinearLayout.VERTICAL
);

root.setPadding(
24 * DP,
20 * DP,
24 * DP,
18 * DP
);

android.graphics.drawable.GradientDrawable rootBg =
new android.graphics.drawable.GradientDrawable();

rootBg.setColor(
android.graphics.Color.rgb(
27,
31,
38
)
);

rootBg.setCornerRadius(
24 * DP
);

root.setBackground(
rootBg
);

android.widget.LinearLayout titleRow =
new android.widget.LinearLayout(
MainActivity.this
);

titleRow.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

root.addView(
titleRow,
new android.widget.LinearLayout.LayoutParams(
-1,
52 * DP
)
);

android.widget.TextView titleIcon =
new android.widget.TextView(
MainActivity.this
);

if (type == 0)
titleIcon.setText("📄");
else if (type == 1)
titleIcon.setText("📁");
else
titleIcon.setText("🗜");

titleIcon.setTextSize(25);

titleIcon.setGravity(
android.view.Gravity.CENTER
);

titleRow.addView(
titleIcon,
new android.widget.LinearLayout.LayoutParams(
48 * DP,
48 * DP
)
);

android.widget.TextView title =
new android.widget.TextView(
MainActivity.this
);

if (type == 0)
title.setText("إنشاء ملف جديد");
else if (type == 1)
title.setText("إنشاء مجلد جديد");
else
title.setText("إنشاء ملف ZIP");

title.setTextColor(
WHITE
);

title.setTextSize(18);

title.setTypeface(
android.graphics.Typeface.DEFAULT,
android.graphics.Typeface.BOLD
);

title.setGravity(
android.view.Gravity.CENTER_VERTICAL
);

titleRow.addView(
title,
new android.widget.LinearLayout.LayoutParams(
0,
-1,
1
)
);

android.widget.TextView hint =
new android.widget.TextView(
MainActivity.this
);

if (type == 0)
hint.setText("اكتب اسم الملف الذي تريد إنشاءه");
else if (type == 1)
hint.setText("اكتب اسم المجلد الذي تريد إنشاءه");
else
hint.setText("اكتب اسم ملف الضغط");

hint.setTextColor(
GRAY
);

hint.setTextSize(11);

hint.setPadding(
4 * DP,
4 * DP,
4 * DP,
10 * DP
);

root.addView(
hint,
new android.widget.LinearLayout.LayoutParams(
-1,
-2
)
);

final android.widget.EditText input =
new android.widget.EditText(
MainActivity.this
);

input.setSingleLine(true);

input.setTextColor(
WHITE
);

input.setHintTextColor(
android.graphics.Color.rgb(
110,
118,
130
)
);

input.setTextSize(15);

if (type == 0)
input.setHint("اسم الملف");
else if (type == 1)
input.setHint("اسم المجلد");
else
input.setHint("اسم ملف ZIP");

input.setPadding(
16 * DP,
0,
16 * DP,
0
);

android.graphics.drawable.GradientDrawable inputBg =
new android.graphics.drawable.GradientDrawable();

inputBg.setColor(
android.graphics.Color.rgb(
20,
24,
30
)
);

inputBg.setCornerRadius(
16 * DP
);

inputBg.setStroke(
1 * DP,
android.graphics.Color.rgb(
55,
63,
75
)
);

input.setBackground(
inputBg
);

root.addView(
input,
new android.widget.LinearLayout.LayoutParams(
-1,
52 * DP
)
);

android.widget.Space space =
new android.widget.Space(
MainActivity.this
);

root.addView(
space,
new android.widget.LinearLayout.LayoutParams(
1,
14 * DP
)
);

android.widget.LinearLayout buttons =
new android.widget.LinearLayout(
MainActivity.this
);

buttons.setOrientation(
android.widget.LinearLayout.HORIZONTAL
);

buttons.setGravity(
android.view.Gravity.CENTER
);

root.addView(
buttons,
new android.widget.LinearLayout.LayoutParams(
-1,
50 * DP
)
);

android.widget.TextView cancel =
new android.widget.TextView(
MainActivity.this
);

cancel.setText("إلغاء");

cancel.setTextColor(
GRAY
);

cancel.setTextSize(14);

cancel.setGravity(
android.view.Gravity.CENTER
);

android.graphics.drawable.GradientDrawable cancelBg =
new android.graphics.drawable.GradientDrawable();

cancelBg.setColor(
android.graphics.Color.rgb(
35,
40,
48
)
);

cancelBg.setCornerRadius(
15 * DP
);

cancel.setBackground(
cancelBg
);

buttons.addView(
cancel,
new android.widget.LinearLayout.LayoutParams(
0,
48 * DP,
1
)
);

android.widget.Space buttonSpace =
new android.widget.Space(
MainActivity.this
);

buttons.addView(
buttonSpace,
new android.widget.LinearLayout.LayoutParams(
10 * DP,
1
)
);

android.widget.TextView create =
new android.widget.TextView(
MainActivity.this
);

create.setText("إنشاء");

create.setTextColor(
WHITE
);

create.setTextSize(14);

create.setTypeface(
android.graphics.Typeface.DEFAULT,
android.graphics.Typeface.BOLD
);

create.setGravity(
android.view.Gravity.CENTER
);

android.graphics.drawable.GradientDrawable createBg =
new android.graphics.drawable.GradientDrawable();

createBg.setColor(
BLUE
);

createBg.setCornerRadius(
15 * DP
);

create.setBackground(
createBg
);

create.setElevation(
4 * DP
);

buttons.addView(
create,
new android.widget.LinearLayout.LayoutParams(
0,
48 * DP,
1
)
);

cancel.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

dialog.dismiss();      
    closeCreateMenu();      
}

}

);

create.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

String n =      
    input.getText()      
    .toString()      
    .trim();      

    if (n.length() == 0) {      

        input.setError(      
            "اكتب اسمًا أولاً"      
        );      

        return;      
    }      

    try {      

        if (type == 0) {      

            new java.io.File(      
                DIR[side],      
                n      
            ).createNewFile();      

        } else if (type == 1) {      

            new java.io.File(      
                DIR[side],      
                n      
            ).mkdirs();      

        } else {      

            if (      
                !n.toLowerCase()      
                .endsWith(".zip")      
            )      
                n += ".zip";      

            java.io.File z =      
            new java.io.File(      
                DIR[side],      
                n      
            );      

            java.io.FileOutputStream o =      
            new java.io.FileOutputStream(z);      

            java.util.zip.ZipOutputStream zz =      
            new java.util.zip.ZipOutputStream(o);      

            zz.close();      
            o.close();      
        }      

        dialog.dismiss();      

        closeCreateMenu();      

        load(0);      
        load(1);      

        android.widget.Toast.makeText(      
            MainActivity.this,      
            "✓ تم الإنشاء بنجاح",      
            android.widget.Toast.LENGTH_SHORT      
        ).show();      

    } catch (      
        Exception e      
    ) {      

        android.widget.Toast.makeText(      
            MainActivity.this,      
            "فشل الإنشاء",      
            android.widget.Toast.LENGTH_SHORT      
        ).show();      
    }      
}

}

);

dialog.setContentView(
root
);

android.view.Window window =
dialog.getWindow();

if (window != null) {

window.setBackgroundDrawable(
new android.graphics.drawable.ColorDrawable(
android.graphics.Color.TRANSPARENT
)
);

window.setDimAmount(
0.65f
);

window.addFlags(
android.view.WindowManager.LayoutParams.FLAG_DIM_BEHIND
);

window.setLayout(
(int)(330 * DP),
android.view.WindowManager.LayoutParams.WRAP_CONTENT
);

}

dialog.setOnShowListener(
new android.content.DialogInterface.OnShowListener() {

@Override
public void onShow(
android.content.DialogInterface d
) {

android.view.Window w =    
    dialog.getWindow();    

    if (w != null) {    

        w.setLayout(    
            (int)(330 * DP),    
            android.view.WindowManager.LayoutParams.WRAP_CONTENT    
        );    

        w.setSoftInputMode(    
            android.view.WindowManager    
            .LayoutParams    
            .SOFT_INPUT_STATE_ALWAYS_VISIBLE    
        );    
    }    

    input.requestFocus();    
}

}

);

dialog.setOnCancelListener(
new android.content.DialogInterface.OnCancelListener() {

@Override
public void onCancel(
android.content.DialogInterface d
) {

closeCreateMenu();      
}

}

);

dialog.show();

}

}

final FM FMAN = new FM();

FMAN.loadSavedConnections();

HIDDEN_DOT.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

boolean state =
!SHOW_HIDDEN[0];

SHOW_HIDDEN[0] =      
state;      

SHOW_HIDDEN[1] =      
state;      

HIDDEN_DOT.setTextColor(      
    state      
    ? BLUE      
    : GRAY      
);      

FMAN.load(0);      
FMAN.load(1);

}

}

);

BACK.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

int s =
ACTIVE[0]
? 0
: 1;

if (REMOTE_MODE[s])
FMAN.remoteBack(s);
else
FMAN.back(s);

}

}

);

FORWARD.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

int s =
ACTIVE[0]
? 0
: 1;

if (!REMOTE_MODE[s])
FMAN.forward(s);

}

}

);

UP.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

int s =
ACTIVE[0]
? 0
: 1;

if (REMOTE_MODE[s])
FMAN.remoteBack(s);
else
FMAN.parent(s);

}

}

);

CREATE.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

FMAN.showCreateMenu();
}

}

);

SYNC.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

int s =
ACTIVE[0]
? 0
: 1;

int o =      
s == 0      
? 1      
: 0;      

DIR[o] =      
DIR[s];      

HISTORY[o].clear();      
FUTURE[o].clear();      

FMAN.load(o);

}

}

);

MORE.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

FMAN.showTopMenu();
}

}

);

MENU_BUTTON.setOnClickListener(
new android.view.View.OnClickListener() {

@Override
public void onClick(
android.view.View v
) {

FMAN.showHamburgerNetworkMenu();

}

}

);

FMAN.load(0);
FMAN.load(1);
FMAN.activate(0);
