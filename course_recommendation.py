import pandas as pd
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB

def get_recommended_courses(user_area_of_interest):
    # Step 1: Prepare the data
    course_data = {
        'course_name': [
            'Machine Learning Fundamentals',
            'Data Visualization Techniques',
            'Statistical Analysis for Data Science',
            'Deep Learning and Neural Networks',
            'Natural Language Processing',
            'Big Data Analytics',
            'Predictive Modeling and Forecasting',
            'Time Series Analysis',
            'Data Mining and Exploration',
            'Data Science Capstone Project',
            'HTML and CSS Basics',
            'JavaScript Fundamentals',
            'Responsive Web Design',
            'Front-End Frameworks (e.g., React)',
            'Back-End Development (e.g., Node.js)',
            'Database Management (e.g., MySQL)',
            'Web Application Security',
            'API Development and Integration',
            'Web Performance Optimization',
            'Full-Stack Web Development',
            'Introduction to Artificial Intelligence',
            'Machine Learning Algorithms',
            'Computer Vision',
            'Natural Language Processing',
            'Deep Learning and Neural Networks',
            'Reinforcement Learning',
            'AI Ethics and Responsible AI',
            'AI in Healthcare',
            'AI in Finance',
            'AI Capstone Project',
            'Introduction to Cyber Security',
            'Network Security',
            'Web Application Security',
            'Ethical Hacking',
            'Digital Forensics',
            'Cryptography',
            'Cyber Security Governance',
            'Security Operations and Incident Response',
            'Penetration Testing',
            'Cyber Security Capstone Project',
            'Python Programming',
            'Java Programming',
            'JavaScript Programming',
            'C++ Programming',
            'Ruby Programming',
            'Go Programming',
            'Swift Programming',
            'R Programming',
            'PHP Programming',
            'Kotlin Programming'
        ],
        'genre': [
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Data Science',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Web Development',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Artificial Intelligence',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Cyber Security',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages',
            'Programming Languages'
        ]
    }

    df = pd.DataFrame(course_data)

    # Step 2: Data preprocessing
    vectorizer = CountVectorizer()
    X = vectorizer.fit_transform(df['course_name'])
    y = df['genre']

    # Step 3: Train the ML model
    model = MultinomialNB()
    model.fit(X, y)

    # Step 4: Make predictions
    user_area_of_interest_vec = vectorizer.transform([user_area_of_interest])

    # Step 5: Retrieve recommended courses
    predicted_genre = model.predict(user_area_of_interest_vec)[0]

    # Filter the dataset based on the predicted genre
    recommended_courses = df[df['genre'] == predicted_genre]['course_name'].tolist()

    # Print the recommended courses
    for course in recommended_courses:
        print(course)


# Retrieve the user's area of interest from command-line argument
import sys
user_area_of_interest = sys.argv[1]

# Get the recommended courses and print them
get_recommended_courses(user_area_of_interest)