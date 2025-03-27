import warnings
warnings.filterwarnings("ignore", category=ResourceWarning)

from flask import Flask, request, jsonify
from flask_cors import CORS  # Pour autoriser les requêtes depuis l'interface web
import pandas as pd
import random
import re
from fuzzywuzzy import fuzz

app = Flask(__name__)
CORS(app)  # Autorise toutes les origines

# ==================== GLOBAL VARIABLES ====================
# Open
current_open_questions = []
current_open_index = 0
current_open_enonce = ""

# QCM
current_qcm_question = {}
remaining_qcm_questions = []

current_mode = None  # 'open' ou 'qcm'
current_theme = 1

# ==================== UTILS ====================
def clean_columns(df):
    # Supprimer les espaces en trop au début, à la fin et remplacer les espaces multiples par un seul
    df.columns = df.columns.str.strip().str.replace(r'\s+', ' ', regex=True)
    return df

def clean_answer(ans):
    if pd.isna(ans): 
        return ""
    return str(ans).strip().lower()

def is_answer_correct(user_answer, correct_answer):
    return fuzz.partial_ratio(clean_answer(user_answer), clean_answer(correct_answer)) > 80

# ==================== LOADERS ====================
def load_open_file(theme_number):
    try:
        df = pd.read_excel(f"open_{theme_number:02d}.xlsx")
        df = clean_columns(df)
        return df
    except Exception as e:
        print(f"❌ Erreur chargement fichier open : {e}")
        return None

def load_qcm_file(theme_number):
    try:
        df = pd.read_excel(f"qcm_{theme_number:02d}.xlsx")
        df = clean_columns(df)
        df = df[[col for col in df.columns if not col.startswith("Unnamed")]]
        return df
    except Exception as e:
        print(f"❌ Erreur chargement fichier qcm : {e}")
        return None

# ==================== ROUTES ====================
@app.route("/ask", methods=["GET"])
def ask():
    global current_open_enonce, current_open_questions, current_open_index
    global remaining_qcm_questions, current_qcm_question, current_mode, current_theme

    try:
        current_theme = int(request.args.get("theme", 1))
        current_mode = request.args.get("type", "qcm")

        if current_mode == "open":
            df = load_open_file(current_theme)
            if df is None:
                return jsonify({"error": "Erreur chargement fichier open"}), 500

            row = df.sample().iloc[0]
            current_open_enonce = row['Enonce']
            current_open_questions = []
            current_open_index = 0

            for col in df.columns:
                if col.startswith("Question") and pd.notnull(row[col]):
                    answer_col = col.replace("Question", "Answer").strip()
                    correct = row[answer_col] if answer_col in row and pd.notnull(row[answer_col]) else ""
                    current_open_questions.append({"question": row[col], "correct": correct})

            if not current_open_questions:
                return jsonify({"error": "Aucune question trouvée."}), 500

            return jsonify({
                "enonce": current_open_enonce,
                "question": current_open_questions[0]["question"]
            })

        elif current_mode == "qcm":
            # Charger le fichier QCM seulement si la file est vide
            if not remaining_qcm_questions:
                df = load_qcm_file(current_theme)
                if df is None:
                    return jsonify({"error": "Erreur chargement fichier QCM"}), 500
                remaining_qcm_questions[:] = df.to_dict(orient="records")
                random.shuffle(remaining_qcm_questions)
            return next_qcm()

        else:
            return jsonify({"error": "Type invalide (open ou qcm)"}), 400

    except Exception as e:
        print(f"❌ Erreur dans /ask : {e}")
        return jsonify({"error": str(e)}), 500

@app.route("/answer", methods=["POST"])
def answer():
    global current_open_index, current_mode, current_qcm_question

    user_answer = request.json.get("answer", "").strip()

    if current_mode == "open":
        question_data = current_open_questions[current_open_index]
        correct_answer = question_data["correct"]

        if is_answer_correct(user_answer, correct_answer):
            result = "✅ Bonne réponse !"
        else:
            result = f"❌ Mauvaise réponse. La bonne réponse était : {correct_answer}"

        return jsonify({
            "result": result,
            "next": current_open_index + 1 < len(current_open_questions)
        })

    elif current_mode == "qcm":
        correct = current_qcm_question.get("Correct Answer", "").strip().upper()
        explanation = current_qcm_question.get("Explication", "Pas d'explication fourni.")

        if user_answer.upper() == correct:
            result = "✅ Bonne réponse !"
        else:
            result = f"❌ Mauvaise réponse. La bonne réponse était : {correct}"

        return jsonify({
            "result": result,
            "explanation": explanation,
            "next": True
        })

@app.route("/next", methods=["GET"])
def next():
    if current_mode == "open":
        global current_open_index
        current_open_index += 1
        if current_open_index < len(current_open_questions):
            return jsonify({"question": current_open_questions[current_open_index]["question"]})
        else:
            return jsonify({"done": True})

    elif current_mode == "qcm":
        return next_qcm()

    else:
        return jsonify({"error": "Mode inconnu."})

def next_qcm():
    global remaining_qcm_questions, current_qcm_question

    if not remaining_qcm_questions:
        print("Aucune question QCM restante.")
        return jsonify({"done": True, "message": "🎉 Plus de questions !"})
    
    current_qcm_question = remaining_qcm_questions.pop(0)
    print("Question QCM retournée:", current_qcm_question.get("Questions", ""))
    return jsonify({
        "enonce": current_qcm_question.get("Questions", ""),
        "answers": {
            "A": current_qcm_question.get("Answer A", ""),
            "B": current_qcm_question.get("Answer B", ""),
            "C": current_qcm_question.get("Answer C", ""),
            "D": current_qcm_question.get("Answer D", ""),
            "E": current_qcm_question.get("Answer E", ""),
            "F": current_qcm_question.get("Answer F", "")
        }
    })


if __name__ == "__main__":
    print("🚀 Flask lancé sur http://localhost:5000")
    app.run(port=5000)
